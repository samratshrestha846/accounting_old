<?php
namespace App\Filters\SuspendedBill;

use App\Filters\FiltersAbstract;

class SuspendedBillFilters extends FiltersAbstract{

    public array $filters = [
        'search' => SearchFilter::class,
        'sort_by' => SortingFilter::class
    ];

}
