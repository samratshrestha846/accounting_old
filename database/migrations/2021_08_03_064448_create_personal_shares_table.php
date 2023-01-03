<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('shareholder_name');
            $table->string('citizenship_no');
            $table->date('citizenship_issue_date');
            $table->string('citizenship');
            $table->string('identity_type');
            $table->string('identity_document');
            $table->bigInteger('total_amount');
            $table->bigInteger('quantity_kitta');
            $table->string('share_type');

            $table->string('grandfather');
            $table->string('father');
            $table->integer('permanent_province_no');
            $table->integer('permanent_district_no');
            $table->string('permanent_local_address');
            $table->integer('temporary_province_no');
            $table->integer('temporary_district_no');
            $table->string('temporary_local_address');
            $table->string('contact_no');
            $table->string('email');
            $table->string('occupation');
            $table->string('marital_status');
            $table->string('spouse_name')->nullable();
            $table->string('spouse_contact_no')->nullable();

            $table->string('benefitiary_name');
            $table->integer('benefitiary_permanent_province_no');
            $table->integer('benefitiary_permanent_district_no');
            $table->string('benefitiary_permanent_local_address');
            $table->integer('benefitiary_temporary_province_no');
            $table->integer('benefitiary_temporary_district_no');
            $table->string('benefitiary_temporary_local_address');
            $table->string('benefitiary_contact_no');
            $table->string('benefitiary_email');
            $table->string('relationship');
            $table->string('benefitiary_citizenship');
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
        Schema::dropIfExists('personal_shares');
    }
}
