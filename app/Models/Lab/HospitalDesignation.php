<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalDesignation extends Model
{
    use HasFactory;
    protected $guarded =  [];

    public $casts = [
        'login' => 'boolean',
    ];
    public function staffs(){
        return $this->hasMany(HospitalStaff::class,'designationId','id');
    }
}
