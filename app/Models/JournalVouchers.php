<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalVouchers extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'journal_voucher_no',
        'entry_date_english',
        'entry_date_nepali',
        'fiscal_year_id',
        'payable_to',
        'debitTotal',
        'creditTotal',
        'payment_method',
        'receipt_payment',
        'bank_id',
        'online_portal_id',
        'cheque_no',
        'customer_portal_id',
        'narration',
        'status',
        'is_cancelled',
        'vendor_id',
        'client_id',
        'entry_by',
        'cancelled_by',
        'approved_by',
        'edited_by',
        'editcount',
    ];

    public function journal_extras()
    {
        return $this->hasMany(JournalExtra::class, 'journal_voucher_id');
    }

    public function fiscal_year()
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id', 'id');
    }

    public function user_entry()
    {
        return $this->hasOne(User::class, 'id', 'entry_by');
    }

    public function user_edit()
    {
        return $this->hasOne(User::class, 'id', 'edited_by');
    }

    public function user_cancel()
    {
        return $this->hasOne(User::class, 'id', 'cancelled_by');
    }

    public function user_approved()
    {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }

    public function supplier()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function online_portal()
    {
        return $this->belongsTo(OnlinePayment::class, 'online_portal_id');
    }
}
