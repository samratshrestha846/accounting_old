<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';

    public function scopeFilters(Builder $query, array $filters): Builder
    {
        $provinceId = Arr::get($filters, 'province_id');

        return $query->when(!is_null($provinceId) , function ($q) use($provinceId){
            $q->where('province_id', $provinceId);
        });
    }

    public function getAll(array $data)
    {
        $filters = Arr::get($data, 'filters');

        if($filters){
            return $this->filters($filters)->get();
        }

        return $this->get();
    }
}
