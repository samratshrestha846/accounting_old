<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningBalance extends Model
{
    use HasFactory;

    protected $fillable = ['child_account_id', 'fiscal_year_id', 'opening_balance', 'closing_balance'];
}
