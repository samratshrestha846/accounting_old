<?php

namespace Database\Seeders;

use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::first();
        $currentCompany = $user->currentCompany;

        $godown = Godown::first();

        $data = [
            [
                'company_id' => $currentCompany->company_id,
                'branch_id' => $currentCompany->branch_id,
                'product_name' => 'Beer',
                'product_code' => 'PD53425578',
                'category_id' => 1,
                'size' => 'Medium',
                'color' => 'Red',
                'opening_stock' => 120,
                'total_stock' => 120,
                'original_vendor_price' => 12000,
                'charging_rate' => 0,
                'final_vendor_price' => 12000,
                'carrying_cost' => 0,
                'transportation_cost' => 0,
                'miscellaneous_percent' => 0,
                'other_cost' => 0,
                'cost_of_product' => 12000,
                'after_custom' => 123,
                'tax' => null,
                'total_cost' => 12000,
                'margin_type' => 'percent',
                'margin_value' => 10,
                'profit_margin' => 120,
                'product_price' =>  12000,
                'description' => "Loream",
                'status' => 1,
                'primary_number' => 1,
                'primary_unit' => 'Box',
                'primary_unit_id' => 1,
                'secondary_number' => 24,
                'secondary_unit' => 'Bottle',
                'secondary_unit_id' => 2,
                'primary_unit_code' => 1,
                'secondary_unit_code' => 2,
                'supplier_id' => 1,
                'brand_id' => 1,
                'series_id' => 1,
                'refundable' => 0,
                'secondary_unit_selling_price' => 120,
            ],
            [
                'company_id' => $currentCompany->company_id,
                'branch_id' => $currentCompany->branch_id,
                'product_name' => 'Sampooo',
                'product_code' => 'PD53425579',
                'category_id' => 1,
                'size' => 'Medium',
                'color' => 'Red',
                'opening_stock' => 20,
                'total_stock' => 20,
                'original_vendor_price' => 10000,
                'charging_rate' => 0,
                'final_vendor_price' => 10000,
                'carrying_cost' => 0,
                'transportation_cost' => 0,
                'miscellaneous_percent' => 0,
                'other_cost' => 0,
                'cost_of_product' => 10000,
                'after_custom' => 100,
                'tax' => null,
                'total_cost' => 10000,
                'margin_type' => 'percent',
                'margin_value' => 10,
                'profit_margin' => 100,
                'product_price' =>  10000,
                'description' => "Loream",
                'status' => 1,
                'primary_number' => 1,
                'primary_unit' => 'Box',
                'primary_unit_id' => 1,
                'secondary_number' => 24,
                'secondary_unit' => 'Bottle',
                'secondary_unit_id' => 2,
                'primary_unit_code' => 1,
                'secondary_unit_code' => 2,
                'supplier_id' => 1,
                'brand_id' => 1,
                'series_id' => 1,
                'refundable' => 0,
                'secondary_unit_selling_price' => 90,
            ],
        ];

        foreach($data as $value){
            $product = Product::create($value);

            GodownProduct::create([
                'company_id' => $currentCompany->company_id ,
                'branch_id' => $currentCompany->branch_id,
                'product_id' => $product->id,
                'godown_id' => 2,
                'floor_no' => 2,
                'rack_no' =>2 ,
                'row_no' =>2 ,
                'opening_stock' => $product->total_stock,
                'stock' => $product->total_stock,
                'alert_on' => 5
            ]);
        }
    }
}
