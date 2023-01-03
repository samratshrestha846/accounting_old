<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GodownSerialNumber extends Model
{
    use HasFactory;

    protected $fillable = ['godown_product_id', 'serial_number', 'status', 'is_damaged', 'is_pos_product', 'is_sold','purchase_billing_id', 'billing_id', 'sales_approved', 'addstock_id'];

    public function godown_product()
    {
        return $this->belongsTo(GodownProduct::class,'godown_product_id','id');
    }
}
