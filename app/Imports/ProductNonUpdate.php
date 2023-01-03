<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class ProductNonUpdate implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $row)
    {
        try{
            foreach($row as $rw){
                $product = Product::where('product_code',$rw['product_code'])->first();
                if($product){
                    $product->update([
                        'total_stock'=>$rw['total_stock'],
                        'total_cost'=>$rw['total_cost'],
                        'product_price'=>$rw['product_price'],
                    ]);
                }
            }
        }catch(\Exception $e)
        {

            throw new \Exception($e->getMessage());
        }

        return false;

        $product = Product::where('product_code', $row['unique_product_code'])->first();

        $final_vendor_price = $row['purchase_price'];
        $profit_margin_percent = $row['profit_margin'] / 100;
        $product_price = $final_vendor_price + ($final_vendor_price * $profit_margin_percent);

        $product->update([
            'original_vendor_price' => $row['purchase_price'],
            'final_vendor_price' => $row['purchase_price'],
            'cost_of_product' => $row['purchase_price'],
            'after_custom' => $row['purchase_price'],
            'total_cost' => $row['purchase_price'],
            'margin_type' => 'percent',
            'margin_value' => $row['profit_margin'],
            'profit_margin' => $row['profit_margin'],
            'product_price' => $row['product_price'],
        ]);
    }
}
