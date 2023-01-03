<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Series extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'series';

    protected $fillable = [
        'brand_id',
        'series_name',
        'series_code'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
