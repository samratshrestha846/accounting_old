<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('allocated_days');
            $table->integer('allocated_bills');
            $table->integer('allocated_amount');

            $table->integer('invoice_id')->nullable();
            $table->date('bill_eng_date')->nullable();
            $table->date('bill_nep_date')->nullable();
            $table->date('bill_expire_eng_date')->nullable();
            $table->date('bill_expire_nep_date')->nullable();
            $table->integer('credited_bills')->default(0);
            $table->integer('credited_amount')->default(0);
            $table->boolean('converted')->default(0);
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
        Schema::dropIfExists('credits');
    }
}
