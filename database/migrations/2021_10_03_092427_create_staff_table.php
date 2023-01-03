<?php

use App\Enums\EmpType;
use App\Enums\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('department_id')->constrained('departments');
            $table->string('employee_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->enum('gender', Gender::getAllValues())->nullable();
            $table->enum('emp_type', EmpType::getAllValues())->nullable();
            $table->string('phone');
            $table->foreignId('position_id')->constrained();
            $table->date('date_of_birth')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->char('postcode')->nullable();
            $table->string('image')->nullable();
            $table->date('join_date')->nullable();
            $table->string('national_id')->nullable();
            $table->string('documents')->nullable();
            $table->string('contract')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();

            $table->unique(['company_id','branch_id','department_id','employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
}
