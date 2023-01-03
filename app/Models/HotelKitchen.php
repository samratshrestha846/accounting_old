<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class HotelKitchen extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'floor_id',
        'room_id',
        'kitchen_name',
        'remarks',
    ];

    public function floor(): BelongsTo
    {
        return $this->belongsTo(HotelFloor::class, 'floor_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HotelRoom::class, 'room_id');
    }

    public function scopeFilters(Builder $query, array $filters): Builder
    {
        return $query->when(Arr::get($filters, 'search'), function ($q, $value) {
            $q->where('name', 'like', '%' . $value . '%');
        });
    }
}
