<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'particulars',
        'quantity',
        'rate',
        'unit',
        'total'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'particulars');
    }
}
