<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyShareHolders extends Model
{
    use HasFactory, SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'company_share_id',
        'shareholder_name',
        'shareholder_contact',
        'shareholder_email',
    ];
}
