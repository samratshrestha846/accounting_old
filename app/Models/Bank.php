<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory, SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'bank_name',
        'head_branch',
        'bank_province_no',
        'bank_district_no',
        'bank_local_address',
        'account_no',
        'account_name',
        'account_type_id',
        'child_account_id'
    ];

    public function province()
    {
        return $this->hasOne(Province::class, 'id', 'bank_province_no');
    }

    public function district()
    {
        return $this->hasOne(District::class, 'id', 'bank_district_no');
    }

    public function account_type()
    {
        return $this->hasOne(BankAccountType::class, 'id', 'account_type_id');
    }

    public function purchaseBillings()
    {
        return $this->hasMany(Billing::class, 'bank_id', 'id')->where('billing_type_id', 2)->where('status', 1);
    }

    public function purchaseReturnBillings()
    {
        return $this->hasMany(Billing::class, 'bank_id', 'id')->where('billing_type_id', 5)->where('status', 1);
    }
    public function salesBillings()
    {
        return $this->hasMany(Billing::class, 'bank_id', 'id')->where('billing_type_id', 1)->where('status', 1);
    }

    public function salesReturnBillings()
    {
        return $this->hasMany(Billing::class, 'bank_id', 'id')->where('billing_type_id', 6)->where('status', 1);
    }

    public function receiptBillings()
    {
        return $this->hasMany(Billing::class, 'bank_id', 'id')->where('billing_type_id', 3)->where('status', 1);
    }

    public function paymentBillings()
    {
        return $this->hasMany(Billing::class, 'bank_id', 'id')->where('billing_type_id', 4)->where('status', 1);
    }
}
