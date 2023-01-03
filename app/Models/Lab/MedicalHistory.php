<?php

namespace App\Models\Lab;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;
    use Multicompany;
    protected $guarded = [];
    protected $casts = [
        'appointmentDate' => 'dateTime',
        'startDate' => 'date',
        'endDate' => 'date',
    ];


}
