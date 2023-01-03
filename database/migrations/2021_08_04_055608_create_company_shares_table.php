<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('total_amount');
            $table->bigInteger('quantity_kitta');
            $table->string('share_type');

            $table->string('company_name');
            $table->string('company_registration_no');
            $table->string('pan_vat_no');
            $table->date('registration_date');
            $table->integer('registered_province_no');
            $table->integer('registered_district_no');
            $table->string('registered_local_address');
            $table->string('company_contact_no');
            $table->string('company_email');
            $table->string('company_capital');
            $table->string('company_paidup_capital');
            $table->longText('work_details');

            $table->string('request_person_name');
            $table->string('designation');
            $table->string('request_contact_no');
            $table->string('request_email');

            $table->string('registration_documents');
            $table->string('pan_vat_document');
            $table->string('request_person_citizenship');
            $table->string('last_year_audit_report');
            $table->string('project_field_report');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_shares');
    }
}
