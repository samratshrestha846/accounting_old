<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductUpdate implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {

        $product = Product::where('product_code', $row['unique_product_code'])->first();

        if ($row['changing_rate'] == null)
        {
            $changing_rate = 1;
        }
        else
        {
            $changing_rate = $row['changing_rate'];
        }

        $final_vendor_price = $row['original_vendor_price'] * $changing_rate;
        $cost_of_product = $final_vendor_price + $row['carrying_cost'] + $row['transportation_cost'] + $row['other_cost'];
        $profit_margin_percent = $row['profit_margin'] / 100;
        $product_price = $cost_of_product + ($cost_of_product * $profit_margin_percent);

        $product->update([
            'original_vendor_price' => $row['original_vendor_price'],
            'charging_rate' => $row['changing_rate'],
            'final_vendor_price' => $row['final_vendor_price'],
            'carrying_cost' => $row['carrying_cost'],
            'transportation_cost' => $row['transportation_cost'],
            'miscellaneous_percent' => $row['miscellaneous_percent'],
            'other_cost' => $row['other_cost'],
            'cost_of_product' => $row['cost_of_product'],
            'custom_duty' => $row['custom_duty'],
            'after_custom' => $row['after_custom'],
            'tax' => $row['tax'],
            'total_cost' => $row['total_cost'],
            'margin_type' => 'percent',
            'margin_value' => $row['profit_margin'],
            'profit_margin' => $row['profit_margin'],
            'product_price' => $row['product_price'],
        ]);
    }
}
