<?php
namespace App\Filters\SuspendedBill;

use App\Filters\FiltersAbstract;
use App\Models\Concerns\HasSearchFilter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter {

    public function filter(Builder $builder, $value): Builder
    {
        return $builder->whereHas('customer', function($q) use($value){
            $q->orWhere('name','like', '%'.$value.'%');
        })->orWhere('created_at','like', '%'.$value.'%')
        ->whereHas('suspendedUser', function($q) use($value){
            $q->Where('name', 'like', '%'.$value.'%');
        })
        ->whereHas('outlet', function($q) use($value){
            $q->orWhere('name', 'like', '%'.$value.'%');
        });;
    }
}
