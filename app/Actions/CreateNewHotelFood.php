<?php
namespace App\Actions;

use App\FormDatas\HotelFoodFormdata;
use App\Models\HotelFood;

class CreateNewHotelFood {
    public function execute(HotelFoodFormdata $data): HotelFood
    {
        return HotelFood::create([
            'kitchen_id' => $data->kitchen_id,
            'category_id' => $data->category_id,
            'food_name' => $data->food_name,
            'food_image' => $data->food_image,
            'component' => $data->component,
            'description' => $data->description,
            'cooking_time' => $data->cooking_time,
            'food_price' => $data->food_price,
            'status' => $data->status
        ]);
    }
}
