<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model {
    use HasFactory, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'general_stock',
        'vendor_id',
        'purchase_order_no',
        'eng_date',
        'nep_date',
        'remarks',
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
        'taxamount',
        'grandtotal',
        'is_printed',
        'is_realtime',
        'printcount',
        'downloadcount',
        'converted'
    ];

    public function suppliers()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    public function purchaseOrderExtras()
    {
        return $this->hasMany(PurchaseOrderExtra::class, 'purchase_order_id');
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
}
