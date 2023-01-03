<?php

namespace App\Filters\Customer;

use App\Exceptions\SortingOrderException;
use Illuminate\Database\Eloquent\Builder;

class SortingFilter
{

    protected array $sortingColumns = [
        'name',
        'email',
        'phone',
        'client_code'
    ];

	protected array $sortingOrder = ['asc','desc'];

    public function filter(Builder $builder, $value): Builder
    {
    	$value = explode('.', $value);

    	$sortBy = $value[0];
    	$orderBy = $value[1] ?? "desc";

		if(!(in_array($sortBy, $this->sortingOrder) || in_array($sortBy, $this->sortingColumns))){
			throw new SortingOrderException("Either sorting key or sorting order does not exist");
		}

		if($orderBy && !in_array($orderBy, $this->sortingOrder)){
    		throw new SortingOrderException("The sorting order must be either asc or desc");
    	}

		if(in_array($sortBy,['asc','desc'])){
			$builder->orderBy('created_at', $sortBy);
		}else{
    		$builder->orderBy($sortBy, $orderBy);
    	}

        return $builder;
    }
}
