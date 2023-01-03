<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
       'company_name',
       'company_email',
       'company_phone',
       'province_id',
       'district_id',
       'address',
       'logo',
       'pan_vat',
       'registration_no',
       'invoice_color',
    ];

    public function province()
    {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    public function district()
    {
        return $this->hasOne(District::class, 'id', 'district_id');
    }
}
