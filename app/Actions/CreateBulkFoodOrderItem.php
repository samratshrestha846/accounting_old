<?php
namespace App\Actions;

use App\Models\HotelOrder;
use App\Models\User;
use Illuminate\Support\Arr;

class CreateBulkFoodOrderItem {

    public function execute(User $user, HotelOrder $hotelOrder, array $foodOrderItems)
    {
        $currentcomp = $user->currentCompany;

        $saleItems = [];

        foreach($foodOrderItems as $content){
            $saleItems[] = [
                'company_id' => $currentcomp->company_id,
                'branch_id' => $currentcomp->branch_id,
                'order_id' => $hotelOrder->id,
                'food_id' => Arr::get($content, 'food_id'),
                'food_name' => Arr::get($content, 'food_name'),
                'quantity' => Arr::get($content, 'quantity'),
                'unit_price' => Arr::get($content, 'unit_price'),
                'tax_type' => Arr::get($content, 'tax_type'),
                'tax_rate_id' => Arr::get($content, 'tax_rate_id'),
                'tax_value' => Arr::get($content, 'tax_value'),
                'discount_type' => Arr::get($content, 'discount_type'),
                'discount_value' => Arr::get($content, 'discount_value'),
                'total_tax' => Arr::get($content, 'total_tax'),
                'total_discount' => Arr::get($content, 'total_discount'),
                'sub_total' => Arr::get($content, 'sub_total'),
                'total_cost' => Arr::get($content, 'total_cost')
            ];
        }

        $hotelOrder->order_items()->insert($saleItems);

        return $saleItems;
    }
}
