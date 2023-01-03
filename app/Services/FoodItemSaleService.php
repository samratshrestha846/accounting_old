<?php
namespace App\Services;

use App\Models\HotelFood;
use App\Models\Tax;
use Illuminate\Support\Arr;

class FoodItemSaleService extends SaleCalculationAbstract{
    //data
    protected ?string $serviceChargeType;
    protected int $serviceChargeRate;
    protected float $cabinCharge;
    protected ?string $taxType;
    protected int $taxRate = 0;
    protected ?string $discountType;
    protected $discountValue = 0;
    protected array $salesItems;
    protected array $results;

    protected bool $syncOfflineData;

    public function __construct(array $salesItems, bool $syncOfflineData = false)
    {
        $this->salesItems = $salesItems;
        $this->syncOfflineData = $syncOfflineData;
        $this->serviceChargeType = "";
        $this->serviceChargeRate = 0;
        $this->cabinCharge = 0;
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
	public function when($value, $callback): self
	{
		if ($value) {
            return $callback($this, $value) ?: $this;
        }

        return $this;
	}

    public function setServiceCharge(string $serviceChargeType, int $serviceChargeRate): self
    {
        $this->serviceChargeType = $serviceChargeType;
        $this->serviceChargeRate = $serviceChargeRate;

        return $this;
    }

    public function setCabinCharge(bool $isCabin, float $cabinCharge):self
    {
        $this->cabinCharge = $cabinCharge;

        return $this;
    }

    public function setTaxRate(string $taxType, int $taxRate):self
    {
        $this->taxType = $taxType;
        $this->taxRate = $taxRate;

        return $this;
    }

    public function setDiscountRate(string $discountType , $discountValue): self
    {
        $this->discountType = $discountType;
        $this->discountValue = $discountValue;

        return $this;
    }

    public function calculate()
    {
        $taxes = Tax::select('id','title','percent')->whereIn(
            'id', collect($this->salesItems)->pluck('tax_rate_id')->toArray()
        )->get();

        $hotelFoodItems = HotelFood::select('id','food_name','food_price')->whereIn(
            'id', collect($this->salesItems)->pluck('food_id')->toArray()
        )->get();

        foreach($this->salesItems as $salesItem){

            $salesItem = (array) $salesItem;

            $purchaseType = Arr::get($salesItem,'purchase_type');
            $tax = collect($taxes)->where('id', $salesItem['tax_rate_id'] ?? null)->first();
            $foodItem = (collect($hotelFoodItems)->where('id', $salesItem['food_id']))->first();
            $foodPrice = $this->syncOfflineData ? (float) ($salesItem['food_price'] ?? 0): (float) ($foodItem['food_price'] ?? 0);
            $quantity = $salesItem['quantity'] ?? 0;
            $taxValue = (float) ($tax['percent'] ?? 0);

            $totalTax = 0;
            $totalDiscount = 0;
            $totalCost = ($foodPrice * $quantity);
            $grossTotal = $totalCost;

            /**
             * if it has no bulk tax, and has setting of indiviudal tax for food sale iem
             *
            */
            if($this->canIndividualTax && !$this->hasBulkTax()){
                if(Arr::get($salesItem, 'tax_rate_id')){

                    $totalTax = $this->calculateTaxRate(
                        $totalCost,
                        strtolower(Arr::get($salesItem, 'tax_type')),
                        $taxValue
                    );
                }
            }

            //if it has setting of individual or both(bulk or indiviudal) discount
            if($this->canIndividualDiscount || $this->canBothDiscount){
                if(Arr::get($salesItem, 'discount_type') && Arr::get($salesItem , 'discount_value')){

                    if(strtolower(Arr::get($salesItem,'discount_type')) == "fixed"){
                        $totalDiscount = $salesItem['discount_value'];
                        $grossTotal = $grossTotal > 0 ? ($grossTotal - $totalDiscount) : $grossTotal;
                    } else if(strtolower(Arr::get($salesItem,'discount_type')) == "percentage") {
                        $totalDiscount = ($totalCost * $salesItem['discount_value'])/100;
                        $grossTotal = $grossTotal > 0 ? ($grossTotal - $totalDiscount) : $grossTotal;
                    }

                }
            }

            $this->results[] = [
                'food_id' => $foodItem->id,
                'food_name' => $foodItem->food_name,
                'quantity' => $quantity,
                'unit_price' => $foodItem->food_price,
                'tax_type' => $this->hasBulkTax() ? null : ($salesItem['tax_type'] ?? null),
                'tax_rate_id' => $this->hasBulkTax() ? null : ($salesItem['tax_rate_id'] ?? null),
                'tax_value' => $this->hasBulkTax() ? null : $taxValue,
                'discount_type' => $salesItem['discount_type'] ?? null,
                'discount_value' => $salesItem['discount_value'] ?? null,
                'total_discount' => $totalDiscount,
                'total_tax' => $totalTax,
                'sub_total' => $totalCost,
                'total_cost' => $grossTotal,
            ];
        }

        $this->salesItems = $this->results;

        return $this;
    }

    public function getContents(): array
    {
        return $this->salesItems;
    }

    // public function getTotalServiceCharge(): float
    // {
    //     $totalServiceCharge = 0;

    //     if(!($this->serviceChargeType && $this->serviceChargeRate))
    //         return $totalServiceCharge;

    //     if(strtolower($this->serviceChargeType) == "fixed") {
    //         return  $this->serviceChargeRate;
    //     }

    //     return ($this->getSubTotal() + $this->serviceChargeRate)/100;
    // }

    // public function getTotalCabinCharge(): float
    // {
    //     return $this->cabinCharge;
    // }

    // public function getTotalDiscount()
    // {
    //     return $this->getBulkDiscount() + collect($this->saleItems)->sum('total_discount');
    // }

    // public function getTotalTax()
    // {
    //     return $this->getBulkTax() + collect($this->saleItems)->sum('total_tax');
    // }

    // public function getSubTotal()
    // {
    //     return collect($this->saleItems)->sum('total_cost');
    // }

    // public function getTotalCost()
    // {
    //     $totalCost = collect($this->saleItems)->sum('total_cost');

    //     if(strtolower($this->taxType) == "exclusive") {
    //         return $totalCost + $this->getTotalServiceCharge() + $this->getBulkTax() - $this->getBulkDiscount() + $this->getTotalCabinCharge();
    //     }

    //     return  $totalCost + $this->getTotalServiceCharge() - $this->getBulkDiscount() + $this->getTotalCabinCharge();
    // }

    // public function getBulkTax()
    // {
    //     $bulkTaxAmt = 0;

    //     if(!($this->canBulkTax && $this->hasBulkTax()))
    //         return $bulkTaxAmt;

    //     return ($this->taxRate * collect($this->saleItems)->sum('total_cost'))/100;
    // }

    // public function getBulkDiscount()
    // {
    //     $bulkDiscountAmt = 0;

    //     if(!(($this->canBulkDiscount || $this->canBothDiscount) && $this->discountType && $this->discountValue))
    //         return $bulkDiscountAmt;

    //     if(strtolower($this->discountType) == 'fixed') {
    //         $bulkDiscountAmt = $this->discountValue;
    //     } else if(strtolower($this->discountType) == "percentage") {
    //         $bulkDiscountAmt = ($this->discountValue * collect($this->saleItems)->sum('total_cost'))/100;
    //     }

    //     return $bulkDiscountAmt;

    // }

    // /**
    //  * Calcualte the Food Item tax value
    //  *
    //  * @param int $totalCost, Tax $tax
    // */
    // public function calcuateFoodItemTaxvalue(int $totalCost,float $taxValue)
    // {
    //     return ($totalCost * $taxValue)/100;
    // }

    // public function hasBulkTax()
    // {
    //     return ($this->taxType && $this->taxRate) ? true: false;
    // }
}
