<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patientId')->nullable();
            $table->unsignedBigInteger('staffId')->nullable();
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->dateTime('appointmentDate')->nullable();
            $table->mediumText('prescription')->nullable();
            $table->mediumText('symptoms')->nullable();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->foreign('patientId')->references('id')->on('patients');
            $table->foreign('staffId')->references('id')->on('hospital_staff');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_histories');
    }
}
