<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelOrderTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'room_id' => $this->room_id,
            'floor_id' => $this->floor_id,
            'is_cabin' => (bool) $this->is_cabin,
            'cabin_charge' => $this->cabin_charge,
            'is_reserved' => $this->busy_orders_count > 0 ? true : false,
            'room' => $this->room,
            'floor' => $this->floor,
        ];
    }
}
