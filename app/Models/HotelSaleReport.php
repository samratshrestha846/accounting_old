<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelSaleReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'food_id',
        'created_by',
        'date',
    ];


    
   
}
