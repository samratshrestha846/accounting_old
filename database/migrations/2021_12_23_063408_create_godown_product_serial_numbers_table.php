<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGodownProductSerialNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('godown_serial_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('godown_product_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('serial_number');
            $table->boolean('status')->default(1);
            $table->boolean('is_damaged')->default(0);
            $table->boolean('is_sold')->default(0);
            $table->bigInteger('purchase_billing_id')->nullable();
            $table->bigInteger('billing_id')->nullable()->default(null);
            $table->boolean('sales_approved')->default(0);
            $table->bigInteger('addstock_id')->nullable();
            $table->timestamps();

            $table->unique(['godown_product_id', 'serial_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('godown_product_serial_numbers');
    }
}
