<?php

namespace App\Actions;

use App\FormDatas\HotelTableFormdata;
use App\Models\HotelTable;

class UpdateHotelTable
{

    public function execute(HotelTable $table, HotelTableFormdata $data): bool
    {
        return $table->update([
            'floor_id' => $data->floor_id,
            'room_id' => $data->room_id,
            'name' => $data->name,
            'code' => $data->code,
            'max_capacity' => $data->max_capacity,
            'is_cabin' => $data->is_cabin,
            'cabin_type_id' => $data->is_cabin ? $data->cabin_type_id : null,
            'cabin_charge' => $data->is_cabin ? $data->cabin_charge : null,
        ]);
    }
}
