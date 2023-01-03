<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductNonImporter extends Model
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
        'purchase_price',
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
