<?php

namespace App\FormDatas;

class HotelTableFormdata
{

    public int $floor_id;
    public int $room_id;
    public string $name;
    public string $code;
    public int $max_capacity;
    public bool $is_cabin;
    public ?int $cabin_type_id;
    public ?float $cabin_charge;

    public function __construct(
        int $floor_id,
        int $room_id,
        string $name,
        string $code,
        int $max_capacity,
        bool $is_cabin,
        ?int $cabin_type_id,
        ?float $cabin_charge
    ) {
        $this->floor_id = $floor_id;
        $this->room_id = $room_id;
        $this->name = $name;
        $this->code = $code;
        $this->max_capacity = $max_capacity;
        $this->is_cabin = $is_cabin;
        $this->cabin_type_id = $cabin_type_id;
        $this->cabin_charge = $cabin_charge;
    }
}
