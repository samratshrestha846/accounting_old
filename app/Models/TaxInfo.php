<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxInfo extends Model
{
    use HasFactory;

    protected $fillable = ['fiscal_year', 'nep_month', 'purchase_tax', 'sales_tax', 'purchasereturn_tax', 'salesreturn_tax', 'total_tax', 'is_paid', 'due', 'paid_at', 'file'];
}
