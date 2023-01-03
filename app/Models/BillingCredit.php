<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingCredit extends Model
{
    use HasFactory;
    protected $fillable=['billing_id','due_date_eng','due_date_nep','credit_amount','vendor_id','client_id','notified','is_read','billing_type_id','is_sale_service'];

    public function billing(){
        return $this->belongsTo(Billing::class);
    }

    public function client(){
        return $this->belongsTo(Client::class,'client_id','id');
    }
    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function sale_bill(){
        return $this->belongsTo(SalesBills::class,'billing_id','id');
    }
}
