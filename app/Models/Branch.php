<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'name', 'email', 'phone', 'province_no', 'district_no', 'local_address', 'is_headoffice'];

    public function companies(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function provinces(){
        return $this->hasOne(Province::class, 'id', 'province_no');
    }
    public function districts(){
        return $this->hasOne(District::class, 'id', 'district_no');
    }
}
