<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class HotelFood extends Model
{
    use HasFactory, Multicompany;

    protected $table = "hotel_foods";

    protected $fillable = [
        'category_id',
        'kitchen_id',
        'food_name',
        'food_image',
        'component',
        'note',
        'description',
        'cooking_time',
        'food_price',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function kitchen(): BelongsTo
    {
        return $this->belongsTo(HotelKitchen::class, 'kitchen_id');
    }

    public function scopeFilters(Builder $query, array $filters): Builder
    {
        return $query->when(Arr::get($filters, 'search'), function ($q, $value) {
            $q->where('food_name', 'like', '%' . $value . '%');
            $q->orWhere('component', 'like', '%' . $value . '%');
            $q->orWhere('food_price', 'like', '%' . $value . '%');
        })
            ->when(Arr::get($filters, 'food_name'), function ($q, $value) {
                $q->where('food_name', 'like', '%' . $value . '%');
            })
            ->when(Arr::get($filters, 'category_id'), function ($q, $value) {
                if(is_array($value)) {
                    $q->whereIn('hotel_foods.category_id', $value);
                } else {
                    $q->where('category_id', $value);
                }
            });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }


    public function billing()
    {
        return $this->belongsToMany(Billing::class, 'hotel_sale_reports', 'food_id', 'billing_id');
    }
}
