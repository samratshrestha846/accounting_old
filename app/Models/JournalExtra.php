<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalExtra extends Model
{
    use HasFactory, Multicompany;

    protected $table = "journal_extras";
    protected $fillable = ['company_id', 'branch_id', 'journal_voucher_id', 'child_account_id', 'code', 'remarks', 'debitAmount', 'creditAmount'];

    public function journal_voucher(){
        return $this->belongsTo(JournalVouchers::class, 'journal_voucher_id', 'id');
    }
    public function fiscal_year(){
        return $this->journal_voucher()->fiscal_year();
    }
    public function child_account(){
        return $this->belongsTo(ChildAccount::class);
    }

}
