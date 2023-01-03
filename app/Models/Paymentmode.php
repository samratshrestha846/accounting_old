<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymentmode extends Model
{
    use HasFactory;

    protected $fillable = ['payment_mode', 'status'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }
}
