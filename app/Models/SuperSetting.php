<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperSetting extends Model {
    use HasFactory;

    protected $fillable = [
            'user_limit',
            'company_limit',
            'branch_limit',
            'expire_date',
            'attendance',
            'notified',
            'journal_edit_number',
            'journal_edit_days_limit',
            'allocated_days',
            'allocated_bills',
            'allocated_amount',
            'before_after',
            'discount_type'
        ];
    }
