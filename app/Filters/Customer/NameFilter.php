<?php
namespace App\Filters\Customer;

use Illuminate\Database\Eloquent\Builder;

class NameFilter {

    public function filter(Builder $builder , $value): Builder
    {
        return $builder->where('name','LIKE', '%'.$value.'%');
    }
}
