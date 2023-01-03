<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyShare extends Model
{
    use HasFactory, SoftDeletes, Multicompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'total_amount',
        'quantity_kitta',
        'share_type',

        'company_name',
        'company_registration_no',
        'pan_vat_no',
        'registration_date',
        'registered_province_no',
        'registered_district_no',
        'registered_local_address',
        'company_contact_no',
        'company_email',
        'company_capital',
        'company_paidup_capital',
        'work_details',

        'request_person_name',
        'designation',
        'request_contact_no',
        'request_email',

        'registration_documents',
        'pan_vat_document',
        'request_person_citizenship',
        'last_year_audit_report',
        'project_field_report',
    ];
}
