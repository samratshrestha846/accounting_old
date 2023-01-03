<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_name',
        'offer_percent',
        'range_min',
        'range_max',
        'offer_start_eng_date',
        'offer_start_nep_date',
        'offer_end_eng_date',
        'offer_end_nep_date',
        'status'
    ];
}
