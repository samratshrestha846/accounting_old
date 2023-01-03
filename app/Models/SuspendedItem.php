<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class SuspendedItem extends Model
{
    use HasFactory, Multicompany;

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(Tax::class,'tax_rate_id','id');
    }

    public function scopeFilters(Builder $query, array $filters): Builder
    {
        $suspendedId = Arr::get($filters, 'suspended_id');
        return $query->when($suspendedId, function($q, $value){
            $q->where('suspended_id', $value);
        });
    }
}
