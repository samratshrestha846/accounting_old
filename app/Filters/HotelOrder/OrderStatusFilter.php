<?php
namespace App\Filters\HotelOrder;

use Illuminate\Database\Eloquent\Builder;

class OrderStatusFilter {
    public function filter(Builder $builder, $value): Builder
    {
        return $builder->where('status', $value);
    }
}
