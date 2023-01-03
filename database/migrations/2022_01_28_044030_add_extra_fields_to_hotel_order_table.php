<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToHotelOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_orders', function (Blueprint $table) {
            $table->foreignId('billing_id')->nullable()->constrained('billings');
            $table->string('service_charge_type')->nullable();
            $table->float('service_charge', 16,2)->nullable();
            $table->float('total_service_charge', 16,2)->nullable();
            $table->text('reason')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('cancled_by')->nullable()->constrained('users');
            $table->foreignId('suspended_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_orders', function (Blueprint $table) {
            //
        });
    }
}
