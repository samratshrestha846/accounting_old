<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Multicompany;

class Appointment extends Model
{
    use HasFactory,softDeletes;
    use Multicompany;

    protected $fillable = [
        'patientId',
        'appointmentdate',
        'startTime',
        'endTime',
        'type',
        'description',
        'status',
        'notes',
        'company_id',
        'branch_id',
        'createdBy',
    ];
    protected $casts = [
        'startTime' => 'datetime',
        'endTime' => 'datetime'
    ];

    public function staffs()
    {
        return $this->belongsToMany(HospitalStaff::class, AppointmentAssignedStaff::class,
          'appoinmentId','staffId');
    }
    public function testTypes()
    {
        return $this->belongsToMany(TestType::class, AppointmentTestType::class,
          'appoinmentId','testTypeId');
    }
    public function patient(){
        return $this->hasOne(Patient::class,'id','patientId');
    }
    public function report(){
        return $this->hasMany(AppointmentReport::class,'appointmentId','id');
    }
}
