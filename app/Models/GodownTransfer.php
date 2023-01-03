<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GodownTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_from',
        'transfer_to',
        'transfered_by',
        'transfered_product',
        'stock',
        'remarks',
        'transfered_nep_date',
        'transfered_eng_date',
    ];
}
