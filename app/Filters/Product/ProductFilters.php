<?php

namespace App\Filters\Product;
use App\Filters\FiltersAbstract;

class ProductFilters extends FiltersAbstract {

    public array $filters = [
        'category_id' => CategoryFilter::class,
        'search' => SearchFilter::class,
        'sort_by' => SortingFilter::class,
        'product_code' => ProductCodeFilter::class,
        'status' => StatusFilter::class,
    ];
}
