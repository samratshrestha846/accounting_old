<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentAssignedStaff extends Model
{
    use HasFactory,softDeletes;

    protected $fillable = [
        'staffId',
        'appoinmentId'
    ];
}
