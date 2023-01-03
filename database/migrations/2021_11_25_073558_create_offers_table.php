<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('offer_name');
            $table->integer('offer_percent');
            $table->integer('range_min')->nullable();
            $table->integer('range_max')->nullable();
            $table->date('offer_start_eng_date');
            $table->string('offer_start_nep_date');
            $table->date('offer_end_eng_date');
            $table->string('offer_end_nep_date');
            $table->boolean('status');
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
        Schema::dropIfExists('offers');
    }
}
