<?php

namespace App\Models\Lab;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalStaff extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function designation()
    {
        return $this->belongsTo(HospitalDesignation::class, 'designationId')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
