<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutletIdSalesRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_records', function (Blueprint $table) {
            $table->foreignId('outlet_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_records', function (Blueprint $table) {
            //
        });
    }
}
