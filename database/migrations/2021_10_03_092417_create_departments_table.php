<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('device_id')->nullable()->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->string('name');
            $table->string('code');
            $table->string('location');
            $table->unsignedInteger('parent_dept')->nullable();
            $table->timestamps();

            $table->unique(['company_id','branch_id','name']);
            $table->unique(['company_id','branch_id','code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
