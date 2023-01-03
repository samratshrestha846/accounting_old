<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('journal_voucher_no')->nullable();
            $table->date('entry_date_english')->nullable();
            $table->date('entry_date_nepali')->nullable();
            $table->foreignId('fiscal_year_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->integer('payable_to')->nullable();
            $table->float('debitTotal', 14,2)->nullable();
            $table->float('creditTotal', 14,2)->nullable();
            $table->integer('payment_method')->nullable();
            $table->integer('receipt_payment')->nullable();
            $table->integer('bank_id')->nullable();
            $table->integer('online_portal_id')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('customer_portal_id')->nullable();
            $table->longText('narration');
            $table->boolean('status')->default(false);
            $table->boolean('is_cancelled');
            $table->integer('entry_by')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('client_id')->nullable()->default(null);
            $table->integer('cancelled_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->integer('editcount')->default(0);
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
        Schema::dropIfExists('journal_vouchers');
    }
}
