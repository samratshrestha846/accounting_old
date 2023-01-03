<?php
namespace App\Actions;

use App\Models\HotelOrder;
use App\Models\Tax;
use App\Services\FoodItemSaleService;
use App\Services\SaleCalculationAbstract;

class UpdateHotelOrderExtraCharges extends SaleCalculationAbstract {

    protected ?string $serviceChargeType = null;
    protected int $serviceChargeRate = 0;
    protected ?string $taxType = null;
    protected int $taxRate = 0;
    protected ?string $discountType = null;
    protected $discountValue = 0;
    protected array $salesItems = [];

    protected bool $syncOfflineData = false;

    public function execute(
        HotelOrder $hotelOrder,
        ?string $tax_type,
        ?int $tax_rate_id,
        ?string $discount_type,
        ?float $discount_value,
        ?float $service_charge_rate,
        string $service_charge_type = "percent"
    ): bool
    {
        $tax = Tax::find($tax_rate_id);

        $this->serviceChargeType = $service_charge_type;
        $this->serviceChargeRate = $service_charge_rate;
        $this->taxType = $tax_type;
        $this->taxRate = $tax ? $tax->percent : 0;
        $this->discountType = $discount_type;
        $this->discountValue = $discount_value;
        $this->cabinCharge = $hotelOrder->is_cabin ? $hotelOrder->cabin_charge : 0;

        $this->salesItems = [
            [
                'total_tax' => 0,
                'total_discount' => 0,
                'sub_total' => $hotelOrder->sub_total,
                'total_cost' => $hotelOrder->sub_total
            ]
        ];

        return $hotelOrder->update([
            'tax_type' => $this->taxType,
            'tax_rate_id' => $tax->id ?? null,
            'tax_value' => $tax->percent ?? null,
            'service_charge_type' => $this->serviceChargeType,
            'service_charge' => $this->serviceChargeRate,
            'discount_type' => $this->discountType,
            'discount_value' => $this->discountValue,
            'total_service_charge' => $this->getTotalServiceCharge(),
            'total_tax' => $this->getTotalTax(),
            'total_discount' => $this->getTotalDiscount(),
            'sub_total' => $this->getSubTotal(),
            'total_cost' => $this->getTotalCost(),
        ]);
    }

}
