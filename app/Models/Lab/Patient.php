<?php

namespace App\Models\Lab;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    use Multicompany;
    public const  GENDER = [
        '0' => 'Male',
        '1' => 'Female',
        '2' => 'Other',
        '3' => 'Not Prefer to say',
    ];

    protected $fillable  = [
        'patientCode',
        'name',
        'address',
        'email',
        'number',
        'date',
        'gender',
        'publishStatus',
        'description',
    ];

    public function history()
    {
        return $this->hasMany(MedicalHistory::class, 'patientId');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patientId');
    }
}
