<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebitCreditNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debit_credit_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('billing_id');
            $table->integer('product_id');
            $table->integer('godown_id');
            $table->bigInteger('amount')->default('0');
            $table->string('notetype')->nullable();
            $table->text('serial_number')->nullable();
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
        Schema::dropIfExists('debit_credit_notes');
    }
}
