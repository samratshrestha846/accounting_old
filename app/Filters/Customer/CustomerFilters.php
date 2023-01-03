<?php
namespace App\Filters\Customer;

use App\Filters\FiltersAbstract;

class CustomerFilters extends FiltersAbstract{

    public array $filters = [
        'name' => NameFilter::class,
        'search' => SearchFilter::class,
        'sort_by' => SortingFilter::class
    ];
}
