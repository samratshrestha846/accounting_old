<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToServiceSalesExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_sales_extras', function (Blueprint $table) {
            //
            $table->float('discountamt', 16,2)->nullable();
            $table->string('discounttype')->nullable();
            $table->float('dtamt', 16, 2)->nullable();
            $table->float('taxamt', 16,2)->nullable();
            $table->float('itemtax', 16,2)->nullable();
            $table->string('taxtype')->nullable();
            $table->string('tax')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_sales_extras', function (Blueprint $table) {
            //
        });
    }
}
