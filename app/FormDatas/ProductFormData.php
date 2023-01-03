<?php
namespace App\FormDatas;

use App\Enums\ProfitMarginType;
use App\Models\Tax;
use Illuminate\Database\DBAL\TimestampType;

class ProductFormData {

    public string $productName;
    public string $productCode;
    public int $categoryId;
    public ?int $supplierId;
    public ?string $declaration_form_no;
    public bool $hasSerialNumber;
    public array $godowns;
    public int $primaryNumber;
    public int $primaryUnitId;
    public ?int $secondaryNumber;
    public ?int $secondaryUnitId;
    public ?int $brandId;
    public ?int $seriesId;
    public ?string $size;
    public ?string $weight;
    public ?string $lotNo;
    public ?string $color;
    public ?string $description;
    public bool $status;
    public bool $refundable;
    public float $originalSupplierPrice;
    public float $changingRate;
    public float $carryingCost;
    public float $transportationCost;
    public float $miscellaneousPercent;
    public float $customDutyPercent;
    public string $profitMarginType;
    public float $profitMarginValue;
    public float $sellingPrice;
    public int $taxId;
    public array $productImages = [];
    public bool $isImporter = false;
    public ?int $warrantyMonths;
    public ?string $manufacturing_date;
    public ?string $expiry_date;
    public ?string $selected_filter_option;
    public string $opening_balance;
    public string $behaviour;

    public function __construct(
        string $productName,
        string $productCode,
        int $categoryId,
        ?int $supplierId,
        ?string $declaration_form_no,
        bool $hasSerialNumber,
        array $godowns,
        int $primaryNumber,
        int $primaryUnitId,
        ?int $secondaryNumber,
        ?int $secondaryUnitId,
        ?int $brandId,
        ?int $seriesId,
        ?string $size,
        ?string $weight,
        ?string $lotNo,
        ?string $color,
        ?string $description,
        bool $status,
        bool $refundable,
        float $originalSupplierPrice,
        float $changingRate,
        float $carryingCost,
        float $transportationCost,
        float $miscellaneousPercent,
        float $customDutyPercent,
        ?string $profitMarginType,
        ?float $profitMarginValue,
        float $sellingPrice,
        int $taxId,
        array $productImages = [],
        ?int $warrantyMonths,
        ?string $manufacturing_date,
        ?string $expiry_date,
        ?string $selected_filter_option,
        string $opening_balance,
        string $behaviour
    )
    {
        $this->productName = $productName;
        $this->productCode = $productCode;
        $this->categoryId = $categoryId;
        $this->supplierId = $supplierId;
        $this->declaration_form_no = $declaration_form_no;
        $this->hasSerialNumber = $hasSerialNumber;
        $this->godowns = $godowns;
        $this->primaryNumber = $primaryNumber;
        $this->primaryUnitId = $primaryUnitId;
        $this->secondaryNumber = $secondaryNumber;
        $this->secondaryUnitId = $secondaryUnitId;
        $this->brandId = $brandId;
        $this->seriesId = $seriesId;
        $this->size = $size;
        $this->weight = $weight;
        $this->lotNo = $lotNo;
        $this->color = $color;
        $this->description = $description;
        $this->status = $status;
        $this->refundable = $refundable;
        $this->originalSupplierPrice = $originalSupplierPrice;
        $this->changingRate = $changingRate;
        $this->carryingCost = $carryingCost;
        $this->transportationCost = $transportationCost;
        $this->miscellaneousPercent = $miscellaneousPercent;
        $this->customDutyPercent = $customDutyPercent;
        $this->profitMarginType = $profitMarginType;
        $this->profitMarginValue = $profitMarginValue;
        $this->sellingPrice = $sellingPrice;
        $this->taxId = $taxId;
        $this->productImages = $productImages;
        $this->warrantyMonths = $warrantyMonths;
        $this->manufacturing_date = $manufacturing_date;
        $this->expiry_date = $expiry_date;
        $this->selected_filter_option = $selected_filter_option;
        $this->opening_balance = $opening_balance;
        $this->behaviour = $behaviour;
    }

    public function setIsImport(bool $isImporter): self
    {
        $this->isImporter = $isImporter;
        if(!$isImporter) {
            $this->resetData();
        }else{
            $this->changingRate = $this->changingRate <= 0 ? 1 : $this->changingRate;
        }

        return $this;
    }

    public function resetData()
    {
        $this->carryingCost = 0;
        $this->transportationCost = 0;
        $this->changingRate = 1;
        $this->miscellaneousPercent = 0;
        $this->customDutyPercent = 0;
        $this->taxId = 0;
    }

    public function getFinalSupplierCost(): float
    {
        return  (float) ($this->originalSupplierPrice * $this->changingRate);
    }

    public function getOtherCost(): float
    {
        $overallcost = ($this->getFinalSupplierCost() + $this->carryingCost + $this->transportationCost);
        return (float) ($overallcost * $this->miscellaneousPercent)/100;
    }

    public function getProductCost(): float
    {
        $overallcost = ($this->getFinalSupplierCost() + $this->carryingCost + $this->transportationCost);

        $miscellaneousValue = ($overallcost * $this->miscellaneousPercent)/100;
        return (float) ($overallcost + $miscellaneousValue);
    }

    public function getCustomAmount(): float
    {
        $productCost = $this->getProductCost();
        return (float) ($productCost + ($productCost * $this->customDutyPercent)/100);
    }

    public function getTotalCost(): float
    {
        $tax = Tax::find($this->taxId);

        $taxValue = $tax ? $tax->percent : 0;

        return (float) ($this->getCustomAmount() + ($this->getCustomAmount() * $taxValue)/100);
    }

    public function getProfitMargin(): float
    {
        $totalCost = $this->getTotalCost();
        $profitMargin = 0;

        if(strtolower($this->profitMarginType) == strtolower(ProfitMarginType::PERCENT) && $this->profitMarginValue){
            $profitMargin = ($this->isImporter ? $totalCost : $this->originalSupplierPrice * $this->profitMarginValue)/100;
        } else if(strtolower($this->profitMarginType) == strtolower(ProfitMarginType::FIXED) && $this->profitMarginValue){
            $profitMargin = $this->profitMarginValue;
        }

        return (float) $profitMargin;
    }

    public function getFinalSellingPrice(): float
    {
        $totalCost = $this->getTotalCost();
        $finalSellingPrice = 0;

        if(strtolower($this->profitMarginType) == strtolower(ProfitMarginType::PERCENT)){
            $finalSellingPrice = $totalCost + ($totalCost * $this->profitMarginValue)/100;
        }else {
            $finalSellingPrice = $totalCost + $this->profitMarginValue;
        }

        return (float) $finalSellingPrice;
    }

    public function getTotalStock(): float
    {
        $totalStock = 0;

        if($this->hasSerialNumber)
        {
            foreach($this->godowns as $godown){
                if(!is_array($godown['serial_numbers'] ?? null))
                    continue;

                $totalStock = $totalStock + count($godown['serial_numbers']);
            }
        }
        else
        {
           $totalStock = collect($this->godowns)->sum('stock');
        }

        return $totalStock;
    }
}
