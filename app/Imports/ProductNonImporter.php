<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildAccount;
use App\Models\FiscalYear;
use App\Models\Godown;
use App\Models\GodownProduct;
use App\Models\GodownSerialNumber;
use App\Models\OpeningBalance;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\Series;
use App\Models\SubAccount;
use App\Models\Unit;
use App\Models\Vendor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use function app\NepaliCalender\datenep;

class ProductNonImporter implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $date = date("Y-m-d");
        $nepalidate = datenep($date);
        $exploded_date = explode("-", $nepalidate);

        $current_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
        $current_fiscal_year = FiscalYear::where('fiscal_year', $current_year)->first();
        $subaccount = SubAccount::where('slug', 'inventory')->first();
        if($subaccount == null){
            $newsubaccount = SubAccount::create([
                'title' => 'Inventory',
                'slug' => Str::slug('Inventory'),
                'account_id' => '1',
                'sub_account_id' => '1'
            ]);
            $newsubaccount->save();
        }

        $subaccount_id = $newsubaccount['id'] ?? $subaccount->id;


     foreach($rows as $row){

        $childAccount = ChildAccount::create([
            'title' => $row["name"] .'('.$row['code'].')',
            'slug' => Str::slug( $row["name"] .'('.$row['code'].')'),
            'opening_balance' => $row['cost'],
            'sub_account_id' => $subaccount_id
        ]);

        $openingbalance = OpeningBalance::create([
            'child_account_id' => $childAccount->id,
            'fiscal_year_id' => $current_fiscal_year->id,
            'opening_balance' => $row['cost'],
            'closing_balance' => $row['cost'],
        ]);

        // $category = Category::find($row['category']);

        Schema::disableForeignKeyConstraints();
        $category_id = ($row['category_id'] == 1) ? 2 : $row['category_id'];


        $new_product = Product::create(
            [
                'product_name' => $row["name"],
                'product_code' => $row['code'],
                'category_id' => $category_id,
                'cost_of_product' => $row['cost'],
                'product_price' => $row['price'],
                'opening_stock' =>0,
                'total_stock' =>0,
                'original_vendor_price' => 0,
                'after_custom' => 0,
                'final_vendor_price' => 0,
                'total_cost' => $row['price'],
                'profit_margin' => $row['price'] - $row['cost'],
                'primary_number' => "Bags",
                'primary_unit'=>"Pieces",
                'primary_unit_id'=>16,
                'primary_unit_code'=>0,
                'secondary_unit_code'=>0,
                'refundable'=>0,
                'has_serial_number'=>0,
                'child_account_id'=>$childAccount->id,

            ]
        );
        Schema::enableForeignKeyConstraints();
        $new_godown_product = GodownProduct::create([
            'product_id' => $new_product->id,
            'godown_id' => 1,
            'opening_stock'=>0,
            'stock' => 0,
            'has_serial_number' => 0,
            'alert_on'=>$row['alert_quantity'],
        ]);
    }

        return false;
        //as.k


        if($row['serial_numbers'] == null)
        {
            $total_stock = $row['total_stock'];
            $has_serial_number = 0;
        } else
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
            'category_id' => $category->id,
            'size' => $row['size'],
            'color' => $row['color'],
            'opening_stock' => $total_stock,
            'total_stock' => $total_stock,
            'original_vendor_price' => $row['purchase_price'],
            'final_vendor_price' => $row['purchase_price'],
            'cost_of_product' => $row['purchase_price'],
            'after_custom' => $row['purchase_price'],
            'total_cost' => $row['purchase_price'],
            'margin_type' => 'percent',
            'margin_value' => $row['profit_margin'],
            'profit_margin' => $row['profit_margin'],
            'product_price' => $row['product_price'],
            'description' => $row['description'],
            'status' => $status,
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
                'opening_stock'=>$stock_by_godown[$i],
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

        $new_product_image = ProductImages::create([
            'product_id' => $new_product->id,
            'location' => 'noimage.jpg'
        ]);
        $new_product_image->save();

        $new_product->save();
    }
}
