<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;

class CategoryFilter
{
    public function filter(Builder $builder, $value): Builder
    {
        return $builder->where('category_id', $value);
    }
}
