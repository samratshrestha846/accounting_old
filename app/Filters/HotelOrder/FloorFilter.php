<?php
namespace App\Filters\HotelOrder;

use Illuminate\Database\Eloquent\Builder;

class FloorFilter {
    public function filter(Builder $builder, $value): Builder
    {
        return $builder->whereHas('tables', function($q) use($value) {
            return $q->where('floor_id', $value);
        });
    }
}
