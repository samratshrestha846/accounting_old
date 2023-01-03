<?php

namespace App\Http\Controllers\API\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Resources\Restaurant\FoodResource;
use App\Models\HotelFood;
use Illuminate\Http\Request;

class FoodItemController extends Controller
{
    public function index(Request $request)
    {
        $this->can(['hotel-order-invoice','hotel-food-view']);

        $perPage = $request->per_page;

        $foodItems =  HotelFood::select([
            'id',
            'food_name',
            'kitchen_id',
            'category_id',
            'food_image',
            'cooking_time',
            'component',
            'notes',
            'description',
            'food_price',
        ])
            ->filters($request->all())
            ->active();


        if($perPage && $perPage > 0){
            $foodItems = $foodItems->paginate($perPage);
        } else {
            $foodItems = $foodItems->get();
        }

       return FoodResource::collection($foodItems);
    }
}
