<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelDeliveryPartner extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'name',
        'email',
        'address',
        'contact_number',
        'logo',
        'province_id',
        'district_id',
        'is_active',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function scopeFilters(Builder $query, array $filters)
    {
        return $query;
    }


    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }
}
