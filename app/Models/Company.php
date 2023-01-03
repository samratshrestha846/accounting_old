<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'province_no', 'district_no', 'local_address', 'registration_no', 'pan_vat', 'ird_sync', 'company_logo', 'is_importer','invoice_color'];

    public function branches(){
        return $this->hasMany(Branch::class, 'company_id', 'id');
    }
    public function provinces(){
        return $this->hasOne(Province::class, 'id', 'province_no');
    }
    public function districts(){
        return $this->hasOne(District::class, 'id', 'district_no');
    }
}
