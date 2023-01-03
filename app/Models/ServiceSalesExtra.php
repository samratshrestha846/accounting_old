<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSalesExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_bills_id',
        'particulars',
        'quantity',
        'purchase_type',
        'purchase_unit',
        'rate',
        'discountamt',
        'discounttype',
        'dtamt',
        'taxamt',
        'itemtax',
        'taxtype',
        'tax',
        'unit',
        'total',
    ];

    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'particulars');
    }
}
