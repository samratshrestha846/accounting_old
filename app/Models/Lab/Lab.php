<?php

namespace App\Models\Lab;

use App\Models\User;
use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;
    use Multicompany;

    protected $guarded = [];

    public function incharge()
    {
        return $this->belongsTo(User::class, 'labIncharge');
    }
}
