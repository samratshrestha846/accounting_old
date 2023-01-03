<?php
namespace App\Filters\Product;

use App\Filters\FiltersAbstract;
use App\Models\Concerns\HasSearchFilter;

class SearchFilter {

    use HasSearchFilter;

    protected $attributes = [
        'product_name',
        'product_code',
    ];
}
