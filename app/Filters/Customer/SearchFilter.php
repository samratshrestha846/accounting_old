<?php
namespace App\Filters\Customer;

use App\Filters\FiltersAbstract;
use App\Models\Concerns\HasSearchFilter;

class SearchFilter {

    use HasSearchFilter;

    protected $attributes = [
        'name',
        'email',
        'phone',
        'client_code'
    ];
}
