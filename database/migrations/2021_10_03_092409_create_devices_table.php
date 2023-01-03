<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('serial_number');
            // $table->foreignId('area_id')->constrained();
            $table->string('ip_address');
            $table->string('area');
            $table->dateTime('last_activity')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->unique(['company_id','branch_id','serial_number']);
            $table->unique(['company_id','branch_id','uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
