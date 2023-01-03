<?php
namespace App\Services;

use App\Enums\ProductPurchase;
use App\Enums\TaxType;
use App\Models\Product;
use App\Models\Tax;
use Illuminate\Support\Arr;

class ProductSaleService {

    //data
    protected array $products;
    protected string $taxType;
    protected int $taxRate = 0;
    protected string $discountType;
    protected $discountValue = 0;
    protected array $salesItems;
    protected array $results;

    protected bool $syncOfflineData;

    //actions
    protected bool $canBulkTax = true;
    protected bool $canIndividualTax = true;
    protected bool $canBulkDiscount = true;
    protected bool $canIndividualDiscount = true;
    protected bool $canBothDiscount = true;

    public function __construct(array $salesItems, bool $syncOfflineData = false)
    {
        $this->salesItems = $salesItems;
        $this->syncOfflineData = $syncOfflineData;
        $this->taxType = "";
        $this->discountType = "";
        $this->discountValue = 0;
        $this->taxRate = 0;
    }

    /***
	 * Callback function when value exist
	 *
	 * @param mixed $data, function $callback
	 * @return self
	 */
	public function when($value, $callback)
	{
		if ($value) {
            return $callback($this, $value) ?: $this;
        }

        return $this;
	}

    public function setTaxRate(string $taxType, int $taxRate)
    {
        $this->taxType = $taxType;
        $this->taxRate = $taxRate;

        return $this;
    }

    public function setDiscountRate(string $discountType , $discountValue)
    {
        $this->discountType = $discountType;
        $this->discountValue = $discountValue;

        return $this;
    }

    public function calculate()
    {
        $taxes = Tax::whereIn(
            'id', collect($this->salesItems)->pluck('tax_rate_id')->toArray()
        )->get();

        $products = Product::whereIn(
            'id', collect($this->salesItems)->pluck('product_id')->toArray()
        )->with('primaryUnit','secondaryUnit')->get();

        foreach($this->salesItems as $salesItem){

            $purchaseType = Arr::get($salesItem,'purchase_type');
            $tax = collect($taxes)->where('id', $salesItem['tax_rate_id'] ?? null)->first();
            $product = (collect($products)->where('id', $salesItem['product_id']))->first();
            $productPrice = $this->syncOfflineData ? (float) ($salesItem['product_price'] ?? 0): (float) ($product['product_price'] ?? 0);
            $quantity = $salesItem['quantity'] ?? 0;
            $taxValue = (float) ($tax['percent'] ?? 0);
            $taxType = Arr::get($salesItem, 'tax_type');

            $totalTax = 0;
            $totalDiscount = 0;
            $subTotal = $purchaseType == ProductPurchase::PRIMARY_UNIT ? ($productPrice * $quantity) : ($product->secondary_unit_selling_price * $quantity);
            $grossTotal = $subTotal;

            //if it has setting of individual or both(bulk or indiviudal) discount
            if($this->canIndividualDiscount || $this->canBothDiscount){
                if(Arr::get($salesItem, 'discount_type') && Arr::get($salesItem , 'value_discount')){
                    $totalDiscount = $this->calculateProductDiscountValue(
                        $subTotal,
                        Arr::get($salesItem,'discount_type'),
                        $salesItem['value_discount'],
                    );
                }
            }

            /**
             * if it has no bulk tax, and has setting of indiviudal tax for product sale iem
             *
            */
            if($this->canIndividualTax && !$this->hasBulkTax()){
                if(Arr::get($salesItem, 'tax_rate_id')){

                    $totalTax = $this->calcuateProductTaxvalue(
                        $subTotal,
                        $totalDiscount,
                        Arr::get($salesItem, 'tax_type'),
                        $taxValue,
                    );
                }
            }

            $this->saleItems[] = [
                'product_id' => $salesItem['product_id'],
                'product_name' => $product->product_name,
                'product_code' => $product->product_code,
                'quantity' => $quantity,
                'unit_price' => $product->product_price,
                'primary_number' => $product->primary_number ?? 0,
                'secondary_number' => $product->secondary_number ?? 0,
                'purchase_type' => $purchaseType,
                'purchase_unit' => $purchaseType == ProductPurchase::PRIMARY_UNIT ? ($product->primaryUnit['short_form'] ?? null): ($product->secondaryUnit['short_form'] ?? null) ,
                'tax_type' => $this->hasBulkTax() ? null : ($salesItem['tax_type'] ?? null),
                'tax_rate_id' => $this->hasBulkTax() ? null : ($salesItem['tax_rate_id'] ?? null),
                'tax_value' => $this->hasBulkTax() ? null : $taxValue,
                'discount_type' => $salesItem['discount_type'] ?? null,
                'discount_value' => $salesItem['value_discount'] ?? null,
                'total_discount' => $totalDiscount,
                'total_tax' => $totalTax,
                'sub_total' => $subTotal,
                'total_cost' => $subTotal - $totalDiscount + (strtolower($taxType) == strtolower(TaxType::EXCLUSIVE) ? $totalTax : 0),
            ];
        }

        return $this;
    }

    public function getContents(): array
    {
        return $this->saleItems;
    }

    public function getTotalDiscount()
    {
        return $this->getBulkDiscount() + collect($this->saleItems)->sum('total_discount');
    }

    public function getTotalTax()
    {
        return $this->getBulkTax() + collect($this->saleItems)->sum('total_tax');
    }

    public function getSubTotal()
    {
        return collect($this->saleItems)->sum('total_cost');
    }

    public function getTotalCost()
    {
        $subTotal = $this->getSubTotal();

        if(strtolower($this->taxType) == strtolower(TaxType::EXCLUSIVE)) {
            return $subTotal + $this->getBulkTax() - $this->getBulkDiscount();
        }

        return  $subTotal - $this->getBulkDiscount();
    }

    public function getBulkTax()
    {
        $bulkTaxAmt = 0;

        if(!($this->canBulkTax && $this->hasBulkTax()))
            return $bulkTaxAmt;

        return $this->calcuateProductTaxvalue(
            $this->getSubTotal(),
            $this->getBulkDiscount(),
            $this->taxType,
            $this->taxRate
        );

        // return ($this->taxRate * collect($this->saleItems)->sum('total_cost'))/100;
    }

    public function getBulkDiscount()
    {
        $bulkDiscountAmt = 0;

        if(!(($this->canBulkDiscount || $this->canBothDiscount) && $this->discountType && $this->discountValue))
            return $bulkDiscountAmt;

        return $this->calculateProductDiscountValue(
                $this->getSubTotal(),
                $this->discountType,
                $this->discountValue

        );

        // if(strtolower($this->discountType) == 'fixed') {
        //     $bulkDiscountAmt = $this->discountValue;
        // } else if(strtolower($this->discountType) == "percentage") {
        //     $bulkDiscountAmt = ($this->discountValue * collect($this->saleItems)->sum('total_cost'))/100;
        // }

        // return $bulkDiscountAmt;

    }

    /**
     * Calcualte the product tax value
     *
     * @param int $totalCost, Tax $tax
    */
    public function calcuateProductTaxvalue(float $subTotal, float $totalDiscount, string $taxType, float $taxValue)
    {
        if(strtolower($taxType) === "exclusive"){
            $totalTax = ($taxValue * ($subTotal - $totalDiscount))/100;
        }
        else if(strtolower($taxType) === "inclusive"){
            $totalTax = ($subTotal * ($taxValue - $totalDiscount))/(100 + $taxValue);
        }

        return $totalTax;
    }

    /**
     * Calculate the product discount value
     *
     * @param float $subTotal,sting $discountType, float $discountValue
     *
    */
    public function calculateProductDiscountValue(float $subTotal, string $discountType, float $discountValue)
    {
        $totalDiscount = 0;

        if(strtolower($discountType) == "fixed"){
            $totalDiscount = $discountValue;
        }
        else if(strtolower($discountType) === "percentage"){
            $totalDiscount = ($subTotal * $discountValue)/100;
        }

        return $totalDiscount;
    }

    public function hasBulkTax()
    {
        return ($this->taxType && $this->taxRate) ? true: false;
    }
}
