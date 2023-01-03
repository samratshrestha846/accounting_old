<?php
namespace App\Filters\HotelOrder;

use Illuminate\Database\Eloquent\Builder;

class OrderDateFilter {
    public function filter(Builder $builder, $value): Builder
    {
        $builder->whereDate('order_at', $value);

        return $builder;
    }
}
