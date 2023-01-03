<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'allocated_days',
        'allocated_bills',
        'allocated_amount',

        'invoice_id',
        'bill_eng_date',
        'bill_nep_date',
        'bill_expire_eng_date',
        'bill_expire_nep_date',
        'credited_bills',
        'credited_amount',
        'converted'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'customer_id');
    }
}
