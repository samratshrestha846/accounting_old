<?php
namespace App\Filters\HotelOrder;

use App\Filters\FiltersAbstract;
use App\Models\Concerns\HasSearchFilter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter {

    public function filter(Builder $builder, $value): Builder
    {
        $builder->where(function($q) use($value){
            return $q->orWhere('id', 'LIKE', '%'.$value.'%')
                ->orWhereHas('customer', function($q) use($value) {
                    $q->where('name','LIKE', '%'.$value.'%');
                })
                ->orWhereHas('waiter', function($q) use($value) {
                    $q->where('name', 'LIKE', '%'.$value.'%');
                })
                ->orWhereHas('tables', function($q) use($value) {
                    $q->where('name', 'LIKE', '%'.$value.'%');
                })
                ->orWhere('total_cost', 'LIKE', '%'.$value.'%');
        });

        return $builder;
    }
}
