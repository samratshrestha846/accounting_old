<?php
namespace App\Filters\HotelOrder;

use Illuminate\Database\Eloquent\Builder;

class TableFilter {
    public function filter(Builder $builder, $value): Builder
    {
        $builder->whereHas('tables', function($q) use($value) {
            return $q->where('table_id', $value);
        });

        return $builder;
    }
}
