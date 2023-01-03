<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelReservation extends Model
{
    use HasFactory, Multicompany;

    protected  $fillable = [
        'table_id',
        'customer_type_id',
        'client_id',
        'number_of_people',
        'date_time_start',
        'date_time_end',
        'payment_method',
        'is_paid',
        'status',
        'amount',
        'date_to_paid',
    ];

    function table()
    {
        return $this->belongsTo(HotelTable::class,'table_id');
    }

    function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }


}
