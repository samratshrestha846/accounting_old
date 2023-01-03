<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Billing extends Model
{
    use HasFactory, Multicompany;

    public const SALES = 1;
    public const PURCHASE = 2;

    protected $fillable = [
        'company_id',
        'branch_id',
        'outlet_id',
        'vendor_id',
        'client_id',
        'billing_type_id',
        'transaction_no',
        'reference_no',
        'ledger_no',
        'file_no',
        'remarks',
        'eng_date',
        'nep_date',
        'payment_method',
        'bank_id',
        'online_portal_id',
        'cheque_no',
        'customer_portal_id',
        'godown',
        'entry_by',
        'edited_by',
        'is_cancelled',
        'cancelled_by',
        'status',
        'approved_by',
        'fiscal_year_id',
        'subtotal',
        'alltaxtype',
        'taxpercent',
        'alltax',
        'alldtamt',
        'alldiscounttype',
        'discountpercent',
        'taxamount',
        'discountamount',
        'service_charge_type',
        'service_charge',
        'servicechargeamount',
        'is_cabin',
        'cabinchargeamount',
        'shipping',
        'grandtotal',
        'reference_invoice_no',
        'sync_ird',
        'is_printed',
        'is_realtime',
        'printcount',
        'vat_refundable',
        'downloadcount',
        'is_pos_data',
        'enable_stock_change',
        'declaration_form_no',
        'related_jv_no'
    ];

    public function billing_credit(){
        return $this->hasOne(BillingCredit::class)->where('is_sale_service',null);
    }
    public function billing_credit_service(){
        return $this->hasOne(BillingCredit::class)->where('is_sale_service',1);
    }
    public function billing_types()
    {
        return $this->belongsTo(Billingtype::class, 'billing_type_id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function godown(): BelongsTo
    {
        return $this->belongsTo(Godown::class,'godown');
    }

    public function billingextras()
    {
        return $this->hasMany(BillingExtra::class, 'billing_id');
    }

    public function fiscal_year()
    {
        return $this->hasOne(FiscalYear::class, 'id', 'fiscal_year_id');
    }

    public function user_edit()
    {
        return $this->hasOne(User::class, 'id', 'edited_by');
    }

    public function user_entry()
    {
        return $this->hasOne(User::class, 'id', 'entry_by');
    }

    public function user_cancel()
    {
        return $this->hasOne(User::class, 'id', 'cancelled_by');
    }

    public function printed_by()
    {
        return $this->hasOne(User::class, 'id', 'printed_by');
    }

    public function user_approve()
    {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }

    public function payment_methods()
    {
        return $this->hasOne(Paymentmode::class, 'id', 'payment_method');
    }

    public function fiscal_years()
    {
        return $this->hasOne(FiscalYear::class, 'id', 'fiscal_year_id');
    }

    public function billprint()
    {
        return $this->hasMany(Billprint::class);
    }

    public function payment_infos()
    {
        return $this->hasMany(PaymentInfo::class);
    }

    public function payment_infos_first()
    {
        return $this->hasOne(PaymentInfo::class);
    }

    public function payment_info_last()
    {
        return $this->hasMany(PaymentInfo::class)->latest();
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function scopeWhereSaleBilling(Builder $query): Builder
    {
        return $query->where('billing_type_id', self::SALES)->where('is_pos_data',0);
    }

    public function scopeWherePurchaseBilling(Builder $query): Builder
    {
        return $query->where('billing_type_id', self::PURCHASE);
    }

    public function scopePos(Builder $query): Builder
    {
        return $query->where('is_pos_data', 1)->where('outlet_id','!=', null);
    }


    public function scopeHotelPosBill(Builder $query): Builder
    {
        return $query->where('billing_type_id', 8);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', \Carbon\Carbon::now());
    }

    public function scopePaymentCash(Builder $query): Builder
    {
        return $query->where('payment_method', 1);
    }

    public function scopePaymentCheque(Builder $query): Builder
    {
        return $query->where('payment_method', 2);
    }

    public function scopeBillingSale(Builder $query): Builder
    {
        return $query->where('billing_type_id', 1);
    }

    public function scopeUserData(Builder $query): Builder
    {
        $user = auth()->user();

        return $query->when($user, function($q) use($user){
            $q->when(!$user->can('manage-pos'), function($q1) use($user){
                $q1->where('entry_by', $user->id);
            });
        });
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function online_portal()
    {
        return $this->belongsTo(OnlinePayment::class, 'online_portal_id');
    }

    public function quotation_followups()
    {
        return $this->hasMany(QuotationFollowup::class);
    }

    public function isCanceled(): bool
    {
        return $this->is_cancelled == 1;
    }
}
