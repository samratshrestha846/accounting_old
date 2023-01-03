<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'child_account_id',
        'fiscal_year',
        'starting_date_english',
        'starting_date_nepali',
        'ending_date_english',
        'ending_date_nepali',
        'budget_allocated',
        'budget_balance',
        'details',
    ];

    public function childaccount(){
        return $this->hasOne(ChildAccount::class, 'id', 'child_account_id');
    }
}
