<?php
namespace App\Filters\Product;

use App\Models\Concerns\HasSearchFilter;

class ProductCodeFilter {
    use HasSearchFilter;

    protected $attributes = [
        'product_code',
    ];
}
