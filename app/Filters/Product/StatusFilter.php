<?php
namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;

class StatusFilter {

    public function filter(Builder $builder, $value): Builder
    {
        return $builder->where('status', $value);
    }
}
