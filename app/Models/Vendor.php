<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory;
    use SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'province_id',
        'district_id',
        'pan_vat',
        'supplier_code',
        'logo',
        'is_client',
        'child_account_id'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function daily_expense()
    {
        return $this->hasMany(DailyExpenses::class);
    }

    protected static $relations_to_cascade = ['daily_expense'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($resource) {
            foreach (static::$relations_to_cascade as $relation) {
                foreach ($resource->{$relation}()->get() as $item) {
                    $item->delete();
                }
            }
        });
    }

    public function vendorconcerns()
    {
        return $this->hasMany(Vendorconcern::class, 'vendor_id');
    }

    public function purchaseBillings()
    {
        return $this->hasMany(Billing::class, 'vendor_id', 'id')->where('billing_type_id', 2)->where('status', 1);
    }


    public function purchaseReturnBillings()
    {
        return $this->hasMany(Billing::class, 'vendor_id', 'id')->where('billing_type_id', 5)->where('status', 1);
    }

    public function servicePurchasebillgrandtotal($id){
        $purchase = SalesBills::where('vendor_id',$id)->where('billing_type_id', 2)->where('status', 1)->sum('grandtotal');
        $purchasereturn = SalesBills::where('vendor_id',$id)->where('billing_type_id', 5)->where('status', 1)->sum('grandtotal');
        return $purchase - $purchasereturn;


    }

    public function servicePurchasebillpaidamount($id){
        $purchase = SalesBills::where('vendor_id',$id)->where('billing_type_id', 2)->where('status', 1)->sum('payment_amount');
        $purchasereturn = SalesBills::where('vendor_id',$id)->where('billing_type_id', 5)->where('status', 1)->sum('payment_amount');
        return $purchase - $purchasereturn;
    }

    public function opening_balance(){
        return $this->hasOne(OpeningBalance::class,'child_account_id','child_account_id');
    }
}
