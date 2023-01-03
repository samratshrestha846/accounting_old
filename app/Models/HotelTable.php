<?php

namespace App\Models;

use App\Enums\OrderItemStatus;
use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class HotelTable extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'floor_id',
        'room_id',
        'name',
        'code',
        'is_cabin',
        'max_capacity',
        'cabin_type_id',
        'cabin_charge',
    ];

    public function floor(): BelongsTo
    {
        return $this->belongsTo(HotelFloor::class, 'floor_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HotelRoom::class, 'room_id');
    }

    public function cabin_type(): BelongsTo
    {
        return $this->belongsTo(CabinType::class, 'cabin_type_id');
    }

    public function reservation()
    {
        return $this->hasMany(HotelReservation::class, 'table_id');
    }

    public function orders()
    {
        return $this->belongsToMany(HotelOrder::class,'hotel_order_table','table_id','order_id');
    }

    public function busyOrders()
    {
        return $this->orders()->where('hotel_orders.status', OrderItemStatus::PENDING);
    }

    public function scopeFilters(Builder $query, array $filters)
    {
        return $query->when(Arr::get($filters, 'search'), function ($q, $value) {
            $q->where('name', 'like', '%' . $value . '%');
            $q->orWhere('code', 'like', '%' . $value . '%');
        })
        ->when(Arr::get($filters,'room_id'), function($q, $value) {
            $q->where('room_id', $value);
        })
        ->when(Arr::get($filters,'floor_id'), function($q, $value) {
            $q->where('floor_id', $value);
        });
    }
}
