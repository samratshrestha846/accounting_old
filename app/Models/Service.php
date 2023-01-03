<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'service_name',
        'service_code',
        'service_category_id',
        'cost_price',
        'sale_price',
        'description',
        'status',
        'child_account_id',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id', 'id');
    }
}
