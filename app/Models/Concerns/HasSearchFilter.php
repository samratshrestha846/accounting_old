<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasSearchFilter
{
    public function filter(Builder $builder, $value): Builder
    {
        foreach ($this->attributes as $attribute){
            $builder = $builder->orWhere($attribute, 'LIKE', "%{$value}%");
        }
        return $builder;
    }
}
