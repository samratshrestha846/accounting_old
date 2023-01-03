<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendorconcern extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'concerned_name',
        'concerned_phone',
        'concerned_email',
        'designation',
        'default'
    ];
}
