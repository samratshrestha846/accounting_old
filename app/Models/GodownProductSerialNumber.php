<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GodownProductSerialNumber extends Model
{
    use HasFactory;

    protected $fillable=[
        'godown_product_id',
        'serial_number',
        'status'
    ];
}
