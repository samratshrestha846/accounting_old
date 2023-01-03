<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerType extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'percent', 'make_user'];
}
