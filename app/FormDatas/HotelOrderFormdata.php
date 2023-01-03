<?php
namespace App\FormDatas;

use App\Models\HotelOrderItem;
use Illuminate\Support\Collection;

class HotelOrderFormdata {

    public int $order_type_id;
    public Collection $tableIds;
    public int $customer_id;
    public ?int $delivery_partner_id;
    public string $order_at;
    public int $waiter_id;
    public int $status;
    public array $order_items;
    public ?string $service_charge_type;
    public ?float $service_charge;
    public bool $is_cabin;
    public ?float $cabin_charge;
    public ?string $tax_type;
    public ?int $tax_rate_id;
    public ?string $discount_type;
    public ?float $discount_value;

    public function __construct(
        int $order_type_id,
        int $customer_id,
        Collection $tableIds,
        ?int $delivery_partner_id,
        string $order_at,
        int $waiter_id,
        int $status,
        ?string $service_charge_type,
        ?float $service_charge,
        bool $is_cabin,
        float $cabin_charge,
        ?string $tax_type,
        ?int $tax_rate_id,
        ?string $discount_type,
        ?float $discount_value
    )
    {
        $this->order_type_id = $order_type_id;
        $this->tableIds = $tableIds;
        $this->customer_id = $customer_id;
        $this->delivery_partner_id = $delivery_partner_id;
        $this->order_at = $order_at;
        $this->waiter_id = $waiter_id;
        $this->status = $status;
        $this->service_charge_type = $service_charge_type;
        $this->service_charge = $service_charge;
        $this->is_cabin = $is_cabin;
        $this->cabin_charge = $cabin_charge;
        $this->tax_type = $tax_type;
        $this->tax_rate_id = $tax_rate_id;
        $this->discount_type = $discount_type;
        $this->discount_value = $discount_value;

        $this->order_items = [];
    }

    public function addOrderItem(HotelOrderItemFormdata ...$hotelOrderItem)
    {
        $this->order_items = $hotelOrderItem;
    }
}
