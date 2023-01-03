<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToSalesBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_bills', function (Blueprint $table) {
            //
            $table->bigInteger('vendor_id')->unsigned()->nullable()->default(null);
            $table->integer('billing_type_id')->default(1);
            $table->string('reference_invoice_no')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_bills', function (Blueprint $table) {
            //
        });
    }
}
