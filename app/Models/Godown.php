<?php

namespace App\Models;

use App\Filters\Godown\GodownFilters;
use App\Models\Pivots\GodownProductSerialNumber;
use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Godown extends Model
{
    use HasFactory, SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'godown_name',
        'province_id',
        'district_id',
        'local_address',
        'godown_code',
        'is_default'
    ];

    public function godownproduct()
    {
        return $this->hasMany(GodownProduct::class, 'godown_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'godown_products')->using(GodownProductSerialNumber::class)->withPivot('id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function scopeFilters(Builder $query, array $filters)
    {
        return (new GodownFilters($filters))->filter($query);
    }

    // public function godownproductimage(){
    //     return $this->product;
    // }
    // public function product(){
    //     return $this->godownproduct()->product();
    // }
}
