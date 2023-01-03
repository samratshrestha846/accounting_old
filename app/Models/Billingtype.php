<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billingtype extends Model
{
    use HasFactory;

    protected $fillable = ['billing_types', 'slug', 'status'];
}
