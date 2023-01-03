<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('payment_type');
            $table->string('payment_amount');
            $table->date('payment_date');
            $table->date('due_date')->nullable();
            $table->string('total_paid_amount')->nullable();
            $table->integer('is_sales_invoice')->nullable();
            $table->bigInteger('paid_to');
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
        Schema::dropIfExists('payment_infos');
    }
}
