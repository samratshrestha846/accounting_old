<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('general_stock');
            $table->integer('vendor_id');
            $table->string('purchase_order_no')->nullable();
            $table->date('eng_date');
            $table->string('nep_date');
            $table->string('remarks');
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
            $table->float('taxamount', 16,2);
            $table->float('grandtotal', 16,2);
            $table->boolean('is_printed')->default(0);
            $table->boolean('is_realtime')->default(0);
            $table->integer('printcount')->default(0);
            $table->integer('downloadcount')->default(0);
            $table->integer('converted')->default(0);
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
        Schema::dropIfExists('purchase_orders');
    }
}
