<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointmentId')->references('id')->on('appointments')->nullable();
            $table->string('report')->nullable();
            $table->mediumText('notes')->nullable();
            $table->foreignId('addedBy')->references('id')->on('users')->nullable();
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
        Schema::dropIfExists('appointment_reports');
    }
}
