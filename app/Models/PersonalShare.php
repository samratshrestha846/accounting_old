<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalShare extends Model
{
    use HasFactory, SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'shareholder_name',
        'citizenship_no',
        'citizenship_issue_date',
        'citizenship',
        'identity_type',
        'identity_document',
        'total_amount',
        'quantity_kitta',
        'share_type',

        'grandfather',
        'father',
        'permanent_province_no',
        'permanent_district_no',
        'permanent_local_address',
        'temporary_province_no',
        'temporary_district_no',
        'temporary_local_address',
        'contact_no',
        'email',
        'occupation',
        'marital_status',
        'spouse_name',
        'spouse_contact_no',

        'benefitiary_name',
        'benefitiary_permanent_province_no',
        'benefitiary_permanent_district_no',
        'benefitiary_permanent_local_address',
        'benefitiary_temporary_province_no',
        'benefitiary_temporary_district_no',
        'benefitiary_temporary_local_address',
        'benefitiary_contact_no',
        'benefitiary_email',
        'relationship',
        'benefitiary_citizenship',
    ];

    protected $dates = [ 'deleted_at' ];
}
