<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\GodownSerialNumber;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\Series;
use App\Models\Unit;
use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd('HI');
        if($row['serial_numbers'] == null)
        {
            $total_stock = $row['total_stock'];
            $has_serial_number = 0;
        }
        else
        {
            $serial_number_array = explode(",", $row['serial_numbers']);
            $total_stock = count($serial_number_array);
            $has_serial_number = 1;
        }
        $godowns = explode(",", $row['godowns_code']);
        $stock_by_godown = explode(",", $row['stock_by_godown']);

        $category = Category::where('category_code', $row['category_code'])->first();
        $supplier = Vendor::where('supplier_code', $row['supplier_code'])->first();
        $brand = Brand::where('brand_code', $row['brand_code'])->first();
        $series = Series::where('series_code', $row['series_code'])->first();

        if ($row['changing_rate'] == null)
        {
            $changing_rate = 1;
        }
        else
        {
            $changing_rate = $row['changing_rate'];
        }

        if($row['status'] == "Active")
        {
            $status = 1;
        }
        else
        {
            $status = 0;
        }

        if($row['refundable'] == "Yes")
        {
            $refundable = 1;
        }
        else
        {
            $refundable = 0;
        }

        if($row['unique_product_code'] == null)
        {
            $product_code = 'PD'.str_pad( mt_rand( 0, 99999999 ), 8, '0', STR_PAD_LEFT );
        }
        else
        {
            $product_code = $row['unique_product_code'];
        }

        $primary_unit = Unit::where('unit_code', $row['primary_unit_code'])->first();
        $secondary_unit = Unit::where('unit_code', $row['secondary_unit_code'])->first();

        $new_product = Product::create([
            'product_name' => $row['product_name'],
            'product_code' => $product_code,
            'category_id' => $row['category'],
            'size' => $row['size'],
            'color' => $row['color'],
            'opening_stock' => $total_stock,
            'total_stock' => $total_stock,
            'original_vendor_price' => $row['original_vendor_price'],
            'charging_rate' => $changing_rate,
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
            'description' => $row['description'],
            'status' => 1,
            'primary_number' => $row['primary_number'],
            'primary_unit' => $primary_unit->unit,
            'primary_unit_id' => $primary_unit->id,
            'primary_unit_code' => $row['primary_unit_code'],
            'secondary_number' => $row['secondary_number'],
            'secondary_unit' => $secondary_unit->unit,
            'secondary_unit_id' => $secondary_unit->id,
            'secondary_unit_code' => $row['secondary_unit_code'],
            'supplier_id' => $supplier->id,
            'brand_id' => $brand->id,
            'series_id' => $series->id,
            'refundable' => $refundable,
            'has_serial_number' => $has_serial_number
        ]);

        for ($i = 0; $i < count($godowns); $i++)
        {
            $related_godown = Godown::where('godown_code', $godowns[$i])->first();
            $new_godown_product = GodownProduct::create([
                'product_id' => $new_product->id,
                'godown_id' => $related_godown->id,
                'opening_stock' => $stock_by_godown[$i],
                'stock' => $stock_by_godown[$i],
                'has_serial_number' => $has_serial_number
            ]);

            if ($has_serial_number == 1) {
                if($i == 0)
                {
                    for($j = 0; $j < $stock_by_godown[$i] ; $j++)
                    {
                        GodownSerialNumber::create([
                            'godown_product_id' => $new_godown_product->id,
                            'serial_number' => $serial_number_array[$j],
                            'status' => 1
                        ]);
                    }
                }
                elseif($i > 0)
                {
                    for($j = $stock_by_godown[$i - 1]; $j < ($stock_by_godown[$i] + $stock_by_godown[$i -1]) ; $j++)
                    {
                        GodownSerialNumber::create([
                            'godown_product_id' => $new_godown_product->id,
                            'serial_number' => $serial_number_array[$j],
                            'status' => 1
                        ]);
                    }
                }
            }

            $new_godown_product->save();
        }

        ProductImages::create([
            'product_id' => $new_product->id,
            'location' => 'noimage.jpg'
        ]);

        $new_product->save();
    }
}
