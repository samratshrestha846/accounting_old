<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reconciliation extends Model
{
    use HasFactory;
    use SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'jv_id',
        // 'manual',
        'bank_id',
        'cheque_no',
        'receipt_payment',
        'amount',
        'cheque_entry_date',
        'cheque_cashed_date',
        'vendor_id',
        'client_id',
        'other_receipt'
    ];

    public function journal(){
        return $this->belongsTo(JournalVouchers::class, 'jv_id', 'id');
    }

    public function bank(){
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    public function client(){
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
