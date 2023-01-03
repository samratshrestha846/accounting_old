<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabinType extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'name',
        'remarks'
    ];
}
