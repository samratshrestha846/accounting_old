<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'id',
        'state',
        'timestamp',
        'type'
    ];

    public function biometric_user()
    {
        return $this->belongsTo(BiometricUser::class, 'id', 'userid');
    }
}
