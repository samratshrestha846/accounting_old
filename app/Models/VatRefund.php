<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatRefund extends Model
{
    use HasFactory;
    protected $fillable = ['fiscal_year_id','fiscal_year','amount','due','total_amount','refunded'];
}
