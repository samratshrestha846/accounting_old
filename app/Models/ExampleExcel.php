<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExampleExcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_code',
        'category',
        'size',
        'color',
        'serial_numbers',
        'total_stock',
        'original_vendor_price',
        'charging_rate',
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
        'description',
        'status',
        'primary_number',
        'primary_unit',
        'secondary_number',
        'secondary_unit',
        'supplier',
        'brand',
        'series',
        'refundable',
        'godowns',
        'stock_by_godown',
        'tips'
    ];
}
