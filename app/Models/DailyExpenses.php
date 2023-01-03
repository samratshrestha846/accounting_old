<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyExpenses extends Model
{
    use HasFactory;
    use SoftDeletes, Multicompany;

    public const UPLOAD_DIRECTORY = "/images/dailyexpenses";

    protected $fillable = [
        'company_id',
        'branch_id',
        'vendor_id',
        'date',
        'bill_image',
        'bill_number',
        'bill_amount',
        'paid_amount',
        'purpose'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
