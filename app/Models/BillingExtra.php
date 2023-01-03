<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'particulars',
        'narration',
        'cheque_no',
        'quantity',
        'purchase_type',
        'purchase_unit',
        'rate',
        'unit',
        'discountamt',
        'discounttype',
        'dtamt',
        'taxamt',
        'itemtax',
        'taxtype',
        'tax',
        'total',
        'particular_type',
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'particulars');
    }

    public function food()
    {
        return $this->hasOne(HotelFood::class, 'id', 'particulars');
    }
}
