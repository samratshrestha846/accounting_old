<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('super_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_limit');
            $table->integer('company_limit');
            $table->integer('branch_limit');
            $table->dateTime('expire_date');
            $table->boolean('attendance');
            $table->boolean('notified');
            $table->integer('journal_edit_number');
            $table->integer('journal_edit_days_limit');
            $table->integer('allocated_days');
            $table->integer('allocated_bills');
            $table->integer('allocated_amount');
            $table->boolean('before_after')->default(1);
            $table->integer('discount_type')->default(2);
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
        Schema::dropIfExists('super_settings');
    }
}
