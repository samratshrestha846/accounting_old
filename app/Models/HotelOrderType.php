<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelOrderType extends Model
{
    use HasFactory;

    const TABLE_ORDER = 1;
    const ONLINE_DELIVERY = 2;
    const TAKE_AWAY = 3;

    protected $fillable = [
        'name',
        'code'
    ];

    public function getAll()
    {
        return $this->select('id','name','code')->get();
    }
}
