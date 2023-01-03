<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentAssignedStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_assigned_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appoinmentId')->references('id')->on('appointments')->nullable();
            $table->foreignId('staffId')->references('id')->on('hospital_staff')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_assigned_staff');
    }
}
