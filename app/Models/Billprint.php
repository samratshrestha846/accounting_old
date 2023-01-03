<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billprint extends Model
{
    use HasFactory;

    protected $fillable = ['billing_id', 'printed_by', 'print_time'];


    public function print_by(){
        return $this->belongsTo(User::class, 'printed_by','id');
    }
}
