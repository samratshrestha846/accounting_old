<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnReference extends Model
{
    use HasFactory;
    protected $fillable = ['billing_id','reference_billing_id','notetype'];
}
