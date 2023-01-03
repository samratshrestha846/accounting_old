<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesBills extends Model
{
    use HasFactory, Multicompany;
    protected $primaryKey = "id";
    protected $fillable = [
        'company_id',
        'branch_id',
        'client_id',
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
        'shipping',
        'grandtotal',
        'payment_type',
        'payment_amount',
        'sync_ird',
        'is_printed',
        'is_realtime',
        'printcount',
        'downloadcount',
        'vat_refundable',
        'vendor_id',
        'billing_type_id',
        'reference_invoice_no',
        'service_charge',
        'servicediscounttype',
        'servicediscountamount',
        'related_jv_no'
    ];

    public function journalvoucher(){
        return $this->belongsTo(JournalVouchers::class,'related_jv_no','journal_voucher_no');
    }

    public function billing_types()
    {
        return $this->belongsTo(Billingtype::class, 'billing_type_id');
    }
    public function billing_credit_service(){
        return $this->hasOne(BillingCredit::class,'billing_id','id')->where('is_sale_service',1);
    }
    public function suppliers()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function serviceSalesExtra()
    {
        return $this->hasMany(ServiceSalesExtra::class);
    }


    public function cancelled_document()
    {
        return $this->hasOne(CancelledServiceBills::class);
    }

    public function fiscal_year()
    {
        return $this->belongsTo(FiscalYear::class);
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

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
    public function online_portal()
    {
        return $this->belongsTo(OnlinePayment::class, 'online_portal_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(Paymentmode::class, 'id', 'payment_method');
    }
}
