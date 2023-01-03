<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class HotelFloor extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'name',
        'code'
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(HotelRoom::class,'floor_id');
    }

    public function scopeFilters(Builder $query, array $filters): Builder
    {
        return $query->when(Arr::get($filters,'search'), function($q, $value){
            $q->where('name','like','%'.$value.'%');
            $q->orWhere('code','like','%'.$value.'%');
        });
    }

    public function hasRooms(): bool
    {
        return $this->rooms()->exists();
    }
}
