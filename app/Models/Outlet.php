<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'name',
        'province_id',
        'district_id',
        'local_address',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function outlet_billers(): HasMany
    {
        return $this->hasMany(OutletBiller::class,'outlet_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'outlet_products')->withPivot(['primary_stock','secondary_stock']);
    }
}
