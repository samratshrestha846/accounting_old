<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrderExtras extends Model
{
    use HasFactory;
    protected $fillable =[
        'client_order_id',
        'particulars',
        'quantity',
        'unit',
    ];
}
