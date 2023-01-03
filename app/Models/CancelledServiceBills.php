<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledServiceBills extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_bills_id',
        'reason',
        'description',
    ];

    public function sales_bills()
    {
        return $this->belongsTo(SalesBills::class);
    }
}
