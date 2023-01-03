<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_refunds', function (Blueprint $table) {
            $table->id();
            $table->integer('fiscal_year_id')->nullable();
            $table->string('fiscal_year')->nullable();
            $table->integer('amount')->default('0');
            $table->integer('due')->default('0');
            $table->integer('total_amount')->default('0');
            $table->integer('refunded')->default('0');
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
        Schema::dropIfExists('vat_refunds');
    }
}
