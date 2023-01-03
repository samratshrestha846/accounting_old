<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSalesExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_sales_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_bills_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('particulars');
            $table->integer('quantity')->nullable();
            $table->string('purchase_type')->nullable();
            $table->string('purchase_unit')->nullable();
            $table->float('rate', 16, 2)->nullable();
            $table->string('unit')->nullable();
            $table->float('total', 16, 2);
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
        Schema::dropIfExists('service_sales_extras');
    }
}
