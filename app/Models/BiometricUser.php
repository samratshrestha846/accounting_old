<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'userid',
        'name',
        'role',
        'password',
        'cardno'
    ];

    public function biometric_attendance()
    {
        return $this->hasMany(BiometricAttendance::class, 'id', 'userid');
    }
}
