<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotationsetting extends Model
{
    use HasFactory;

    protected $fillable = ['show_brand', 'show_model', 'show_picture', 'letterhead'];
}
