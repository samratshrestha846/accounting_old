<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReconciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('jv_id')->nullable();
            // $table->integer('manual')->nullable();
            $table->integer('bank_id');
            $table->string('cheque_no')->nullable();
            $table->boolean('receipt_payment');
            $table->string('amount');
            $table->date('cheque_entry_date');
            $table->date('cheque_cashed_date')->nullable();
            $table->integer('vendor_id')->nullable()->default(null);
            $table->integer('client_id')->nullable()->default(null);
            $table->string('other_receipt')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('reconciliations');
    }
}
