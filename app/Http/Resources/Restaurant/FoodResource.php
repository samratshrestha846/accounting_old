<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class FoodResource extends JsonResource
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
            'food_name' => $this->food_name,
            'kitchen_id' => $this->kitchen_id,
            'category_id' => $this->category_id,
            'food_image' => Storage::disk('uploads')->url($this->food_image),
            'cooking_time' => $this->cooking_time,
            'component' => $this->component,
            'notes' => $this->notes,
            'description' => $this->description,
            'food_price' => $this->food_price,
            'category' => FoodCategoryResource::make($this->whenLoaded('category')),
            'kitchen' => FoodKitchenResource::make($this->whenLoaded('kitchen')),
        ];
    }
}
