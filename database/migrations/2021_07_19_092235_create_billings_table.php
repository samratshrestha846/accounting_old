<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('vendor_id')->nullable()->default(null);
            $table->integer('client_id')->nullable()->default(null);
            $table->integer('billing_type_id');
            $table->string('transaction_no')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('ledger_no')->nullable();
            $table->string('file_no')->nullable()->default(null);
            $table->string('remarks')->nullable();
            $table->date('eng_date');
            $table->string('nep_date');
            $table->integer('payment_method')->nullable();
            $table->integer('bank_id')->nullable();
            $table->integer('online_portal_id')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('customer_portal_id')->nullable();
            $table->integer('godown')->nullable()->default(null);
            $table->integer('entry_by');
            $table->integer('edited_by')->nullable();
            $table->boolean('is_cancelled')->default(0);
            $table->integer('cancelled_by')->nullable();
            $table->boolean('status');
            $table->integer('approved_by')->nullable();
            $table->integer('fiscal_year_id');
            $table->float('subtotal', 16,2);
            $table->string('alltaxtype')->nullable();
            $table->string('taxpercent')->nullable();
            $table->string('alltax')->nullable();
            $table->string('alldtamt')->nullable();
            $table->string('alldiscounttype')->nullable();
            $table->string('discountpercent')->nullable();
            $table->float('taxamount', 16,2);
            $table->float('discountamount', 16,2);
            $table->float('shipping', 16,2)->default(0);
            $table->float('grandtotal', 16,2);
            $table->string('reference_invoice_no')->nullable()->default(null);
            $table->boolean('sync_ird')->default(0);
            $table->boolean('is_printed')->default(0);
            $table->boolean('is_realtime')->default(0);
            $table->integer('printcount')->default(0);
            $table->integer('downloadcount')->default(0);
            $table->string('vat_refundable')->nullable()->default(null);
            $table->boolean('is_pos_data')->default(0);
            $table->text('selected_filter_option')->nullable();
            $table->integer('enable_stock_change')->nullable();
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
        Schema::dropIfExists('billings');
    }
}
