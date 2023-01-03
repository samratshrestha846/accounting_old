<?php

namespace App\Models;

use App\Filters\Customer\CustomerFilters;
use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = ['company_id', 'branch_id', 'client_type', 'name', 'client_code', 'pan_vat', 'phone', 'email', 'province', 'district', 'local_address', 'dealer_type_id', 'logo','is_vendor', 'child_account_id'];

    public function provinces()
    {
        return $this->belongsTo(Province::class, 'province');
    }

    public function districts()
    {
        return $this->belongsTo(District::class, 'district');
    }

    public function daily_expense()
    {
        return $this->hasMany(DailyExpenses::class);
    }

    public function scopeFilters(Builder $query, array $filters)
    {
        return (new CustomerFilters($filters))->filter($query);
    }

    public function clientconcerns()
    {
        return $this->hasMany(Clientconcern::class);
    }
    public function dealertype()
    {
        return $this->belongsTo(DealerType::class, 'dealer_type_id');
    }

    public function credits()
    {
        return $this->hasMany(Credit::class, 'customer_id', 'id');
    }

    public function dealer_users(){
        return $this->hasMany(DealerUser::class, 'client_id');
    }

    public function salesBillings()
    {
        return $this->hasMany(Billing::class, 'client_id', 'id')->where('billing_type_id', 1)->where('status', 1);
    }

    public function salesReturnBillings()
    {
        return $this->hasMany(Billing::class, 'client_id', 'id')->where('billing_type_id', 6)->where('status', 1);
    }

    public function serviceSalebillgrandtotal($id){
        $sale = SalesBills::where('client_id',$id)->where('billing_type_id', 1)->where('status', 1)->sum('grandtotal');
        $salereturn = SalesBills::where('client_id',$id)->where('billing_type_id', 6)->where('status', 1)->sum('grandtotal');
        return $sale - $salereturn ;


    }

    public function serviceSalebillpaidamount($id){
        $sale = SalesBills::where('client_id',$id)->where('billing_type_id', 1)->where('status', 1)->sum('payment_amount');
        $salereturn = SalesBills::where('client_id',$id)->where('billing_type_id', 6)->where('status', 1)->sum('payment_amount');
        return $sale - $salereturn ;
    }

    public function opening_balance(){
        return $this->hasOne(OpeningBalance::class,'child_account_id','child_account_id');
    }
}


