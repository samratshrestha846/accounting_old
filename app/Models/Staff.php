<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    use HasFactory, Multicompany;

    protected $fillable = [
        'employee_id',
        'department_id',
        'position_id',
        'first_name',
        'last_name',
        'email',
        'gender',
        'emp_type',
        'phone',
        'date_of_birth',
        'city',
        'address',
        'postcode',
        'join_date',
        'image',
        'national_id',
        'documents',
        'contract',
        'status',
    ];

    public function position()
    {
        return $this->hasOne(Position::class, 'id' ,'position_id');
    }

    public function department():BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id' ,'id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Check whether device id belongs to staff or not
     *
     * @return boolean
    */
    public function belongsDeviceId($deviceId): bool
    {
        return $this->department()->where('device_id', $deviceId)->exists();
    }
}
