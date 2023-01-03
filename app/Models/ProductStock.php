<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','godown_id', 'added_stock', 'added_date','billing_id'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function godown(){
        return $this->belongsTo(Godown::class, 'godown_id');
    }
    public function godownproduct(){
        return $this->belongsTo(GodownProduct::class, 'godown_id', 'godown_id');
    }
    public function billings(){
        return $this->belongsTo(Billing::class, 'billing_id');
    }
}
