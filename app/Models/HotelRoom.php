<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class HotelRoom extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'name',
        'code',
        'floor_id',
        'table_capacity'
    ];

    public function floor(): BelongsTo
    {
        return $this->belongsTo(HotelFloor::class,'floor_id');
    }

    public function scopeFilters(Builder $query, array $filters): Builder
    {
        return $query->when(Arr::get($filters,'search'), function($q, $value){
            $q->where('name','like','%'.$value.'%');
            $q->orWhere('code','like','%'.$value.'%');
            $q->orWhere('table_capacity','like','%'.$value.'%');
        })->when(Arr::get($filters,'floor_id'), function($q, $value) {
            $q->where('floor_id', $value);
        });;
    }
}
