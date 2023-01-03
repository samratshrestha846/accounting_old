<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebitCreditNote extends Model
{
    use HasFactory;
    protected $fillable = ['billing_id','product_id','godown_id','amount','notetype','serial_number'];
}
