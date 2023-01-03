<?php

namespace Database\Seeders;

use App\Models\DealerType;
use Illuminate\Database\Seeder;

class DealerTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DealerType::insert([
            [
                'id' => 1,
                'title'=>'Partner',
                'percent'=>30,
                'is_default'=>0,
                'make_user'=>1,
            ],
            [
                'id' => 2,
                'title'=>'Dealer',
                'percent'=>20,
                'is_default'=>0,
                'make_user'=>1,
            ],
            [
                'id' => 3,
                'title'=>'Agent',
                'percent'=>20,
                'is_default'=>0,
                'make_user'=>1,
            ],
            [
                'id' => 4,
                'title'=>'Retailer',
                'percent'=>10,
                'is_default'=>0,
                'make_user'=>0,
            ],
            [
                'id' => 5,
                'title'=>'Counter Sales',
                'percent'=>0,
                'is_default'=>0,
                'make_user'=>0,
            ],
            [
                'id' => 6,
                'title'=>'MRP',
                'percent'=>0,
                'is_default'=>1,
                'make_user'=>0,
            ],
        ]);
    }
}
