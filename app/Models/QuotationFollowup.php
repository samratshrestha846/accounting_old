<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationFollowup extends Model
{
    use HasFactory;

    protected $fillable = ['billing_id', 'followup_date', 'followup_title', 'followup_details', 'is_followed', 'is_notified'];
}
