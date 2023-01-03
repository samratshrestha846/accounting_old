<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOutProduct extends Model
{
    use HasFactory;
    protected $fillable = ['stock_out_id', 'product_id', 'total_stock_out'];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
