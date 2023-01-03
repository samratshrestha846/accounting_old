<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerUserCompany extends Model
{
    use HasFactory;

    protected $fillable = ['dealer_user_id', 'company_id', 'branch_id', 'is_selected'];

    public function dealeruser()
    {
        return $this->belongsTo(DealerUser::class, 'dealer_user_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
