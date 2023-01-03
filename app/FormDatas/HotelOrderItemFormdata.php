<?php
namespace App\FormDatas;

class HotelOrderItemFormdata {
    public int $order_id;
    public int $food_id;
    public int $quantity;
    public ?string $tax_type;
    public ?int $tax_rate_id;
    public ?string $discount_type;
    public ?float $discount_value;

    public function __construct(
        int $order_id,
        int $food_id,
        int $quantity,
        ?string $tax_type,
        ?int $tax_rate_id,
        ?string $discount_type,
        ?float $discount_value
    )
    {
        $this->order_id = $order_id;
        $this->food_id = $food_id;
        $this->quantity = $quantity;
        $this->tax_type = $tax_type;
        $this->tax_rate_id = $tax_rate_id;
        $this->discount_type = $discount_type;
        $this->discount_value = $discount_value;
    }
}
