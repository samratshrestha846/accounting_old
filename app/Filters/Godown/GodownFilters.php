<?php
namespace App\Filters\Godown;

use App\Filters\FiltersAbstract;

class GodownFilters extends FiltersAbstract{

    public array $filters = [
        'products' => ProductListFilter::class,
    ];
}
