<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'category_name',
        'category_code',
        'category_image',
        'in_order'
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'service_category_id');
    }
}
