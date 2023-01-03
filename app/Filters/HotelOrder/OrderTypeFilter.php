<?php
namespace App\Filters\HotelOrder;

use Illuminate\Database\Eloquent\Builder;

class OrderTypeFilter {
    public function filter(Builder $builder, $value): Builder
    {
        $builder->where('order_type_id', $value);

        return $builder;
    }
}
