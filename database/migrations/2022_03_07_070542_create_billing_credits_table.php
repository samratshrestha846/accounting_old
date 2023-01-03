<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_credits', function (Blueprint $table) {
            $table->id();
            $table->integer('billing_id');
            $table->string('due_date_eng')->nullable();
            $table->string('due_date_nep')->nullable();
            $table->string('credit_amount')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('notified')->default(0);
            $table->integer('is_read')->default(0);
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
        Schema::dropIfExists('billing_credits');
    }
}
