<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Multicompany;

class TestType extends Model
{
    use HasFactory,softDeletes;
    use Multicompany;
    protected $guarded = [];
}
