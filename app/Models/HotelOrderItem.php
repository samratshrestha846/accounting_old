<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelOrderItem extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'order_id',
        'food_id',
        'food_name',
        'quantity',
        'unit_price',
        'tax_type',
        'tax_rate_id',
        'tax_value',
        'discount_type',
        'discount_value',
        'total_tax',
        'total_discount',
        'sub_total',
        'total_cost'
    ];

    public function scopeSelectedAttr(Builder $query): Builder
    {
        return $query->select([
            'order_id',
            'food_id',
            'food_name',
            'quantity',
            'unit_price',
            'tax_type',
            'tax_rate_id',
            'tax_value',
            'discount_type',
            'discount_value',
            'total_tax',
            'total_discount',
            'sub_total',
            'total_cost',
        ]);
    }

    public function food(): BelongsTo
    {
        return $this->belongsTo(HotelFood::class,'food_id');
    }
}
