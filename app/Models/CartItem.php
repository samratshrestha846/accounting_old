<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_name',
        'product_code',
        'product_price',
        'quantity',
        'tax_type',
        'tax_rate_id',
        'tax_value',
        'discount_type',
        'discount_value'
    ];
}
