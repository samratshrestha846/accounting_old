<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateNonImporter extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'purchase_price',
        'profit_margin',
        'product_price',
    ];
}
