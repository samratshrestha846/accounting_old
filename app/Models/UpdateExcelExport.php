<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateExcelExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'original_vendor_price',
        'changing_rate',
        'final_vendor_price',
        'carrying_cost',
        'transportation_cost',
        'miscellaneous_percent',
        'other_cost',
        'cost_of_product',
        'custom_duty',
        'after_custom',
        'tax',
        'total_cost',
        'profit_margin',
        'product_price',
    ];
}
