<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledPurchaseOrders extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'reason',
        'description'
    ];
}
