<?php

namespace Database\Seeders;

use App\Models\HotelOrder;
use App\Models\HotelOrderType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelOrderTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'id' => 1,
                'name' => 'Dine in Customer',
                'code' => '001',
            ],
            [
                'id' => 2,
                'name' => 'Online Delivery',
                'code' => '002',
            ],
            [
                'id' => 3,
                'name' => 'Take Away',
                'code' => '003',
            ]
        ];

        foreach($items as $value) {
            HotelOrderType::updateOrCreate($value);
        }


        HotelOrder::query()->whereNull('order_type_id')->update([
            'order_type_id' => 1,
        ]);

    }
}
