<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledBilling extends Model
{
    use HasFactory;

    protected $fillable = ['billing_id', 'reason', 'description'];
}
