<?php
namespace App\Filters\Godown;

use Illuminate\Database\Eloquent\Builder;

class ProductListFilter {

    public function filter(Builder $builder , $value): Builder
    {
        if(!is_array($value))
            return $builder;

        return $builder->whereHas('godownproduct',function($q) use($value){
            return $q->whereIn('product_id', $value);
        });
    }
}
