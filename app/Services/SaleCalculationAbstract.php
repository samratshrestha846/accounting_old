<?php
namespace App\Services;

use App\Enums\TaxType;

abstract class SaleCalculationAbstract {

    //data
    protected ?string $serviceChargeType = null;
    protected int $serviceChargeRate = 0;
    protected float $cabinCharge = 0;
    protected ?string $taxType = null;
    protected int $taxRate = 0;
    protected ?string $discountType = null;
    protected $discountValue = 0;
    protected array $salesItems = [];

    protected bool $syncOfflineData = false;

    //actions
    protected bool $canBulkTax = true;
    protected bool $canIndividualTax = true;
    protected bool $canBulkDiscount = true;
    protected bool $canIndividualDiscount = true;
    protected bool $canBothDiscount = true;


    /***
	 * Callback function when value exist
	 *
	 * @param mixed $data, function $callback
	 * @return self
	 */
	// abstract public function when($value, $callback): self;

    // abstract public function setServiceCharge(string $serviceChargeType, int $serviceChargeRate): self;

    // abstract public function setCabinCharge(bool $isCabin, float $cabinCharge): self;

    // abstract public function setTaxRate(string $taxType, int $taxRate): self;

    // abstract public function setDiscountRate(string $discountType , $discountValue):self;

    public function getTotalServiceCharge(): float
    {
        $totalServiceCharge = 0;

        if(!($this->serviceChargeType && $this->serviceChargeRate))
            return $totalServiceCharge;

        if(strtolower($this->serviceChargeType) == "fixed") {
            return  $this->serviceChargeRate;
        }

        return ($this->getSubTotal() * $this->serviceChargeRate)/100;
    }

    public function getTotalCabinCharge(): float
    {
        return $this->cabinCharge;
    }

    public function getTotalDiscount()
    {
        return $this->getBulkDiscount() + collect($this->salesItems)->sum('total_discount');
    }

    public function getTotalTax()
    {
        return $this->getBulkTax() + collect($this->salesItems)->sum('total_tax');
    }

    public function getSubTotal()
    {
        return collect($this->salesItems)->sum('total_cost');
    }

    public function getTotalCost()
    {
        $totalCost = collect($this->salesItems)->sum('total_cost');

        if(strtolower($this->taxType) == "exclusive") {
            return $totalCost + $this->getTotalServiceCharge() + $this->getBulkTax() - $this->getBulkDiscount() + $this->getTotalCabinCharge();
        }

        return  $totalCost + $this->getTotalServiceCharge() - $this->getBulkDiscount() + $this->getTotalCabinCharge();
    }

    public function getBulkTax()
    {
        $bulkTaxAmt = 0;

        if(!($this->canBulkTax && $this->hasBulkTax()))
            return $bulkTaxAmt;

        return $this->calculateTaxRate(
            collect($this->salesItems)->sum('total_cost'),
            $this->taxType,
            (float) $this->taxRate
        );
    }

    public function getBulkDiscount()
    {
        $bulkDiscountAmt = 0;

        if(!(($this->canBulkDiscount || $this->canBothDiscount) && $this->discountType && $this->discountValue))
            return $bulkDiscountAmt;

        if(strtolower($this->discountType) == 'fixed') {
            $bulkDiscountAmt = $this->discountValue;
        } else if(strtolower($this->discountType) == "percentage") {
            $bulkDiscountAmt = ($this->discountValue * collect($this->salesItems)->sum('total_cost'))/100;
        }

        return $bulkDiscountAmt;

    }

    public function calculateTaxRate(float $total, string $taxType, float $taxRate): float
    {

        $totalTax = 0;

        if(strtolower($taxType) == strtolower(TaxType::INCLUSIVE)) {
            $totalTax = ($total * $taxRate)/(100 + $taxRate);
        } else if (strtolower($taxType) == strtolower(TaxType::EXCLUSIVE)) {
            $totalTax = ($total * $taxRate)/100;
        }

        return $totalTax;
    }

    public function hasBulkTax()
    {
        return ($this->taxType && $this->taxRate) ? true: false;
    }
}
