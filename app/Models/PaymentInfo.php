<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInfo extends Model
{
    use HasFactory;
    protected $fillable = ['billing_id', 'payment_type', 'payment_amount', 'payment_date', 'due_date', 'total_paid_amount', 'paid_to', 'is_sales_invoice'];

    const PAYMENT_TYPE = [
        'unpaid' => 'unpaid',
        'partially_paid' => 'partially_paid',
        'paid' => 'paid',
    ];

    public function user_entry()
    {
        return $this->hasOne(User::class, 'id', 'paid_to');
    }
    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
