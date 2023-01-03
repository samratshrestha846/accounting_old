<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGodownTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('godown_transfers', function (Blueprint $table) {
            $table->id();
            $table->integer('transfer_from');
            $table->integer('transfer_to');
            $table->integer('transfered_by');
            $table->integer('transfered_product');
            $table->bigInteger('stock');
            $table->longText('remarks');
            $table->date('transfered_nep_date');
            $table->date('transfered_eng_date');
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
        Schema::dropIfExists('godown_transfers');
    }
}
