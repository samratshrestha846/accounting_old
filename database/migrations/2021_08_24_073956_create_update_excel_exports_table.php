<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateExcelExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_excel_exports', function (Blueprint $table) {
            $table->id();
            $table->string('product_code');
            $table->string('original_vendor_price');
            $table->string('changing_rate')->nullable();
            $table->string('final_vendor_price');
            $table->string('carrying_cost')->nullable();
            $table->string('transportation_cost')->nullable();
            $table->string('miscellaneous_percent')->nullable();
            $table->string('other_cost')->nullable();
            $table->string('cost_of_product');
            $table->string('custom_duty')->nullable();
            $table->string('after_custom');
            $table->string('tax')->nullable();
            $table->string('total_cost');
            $table->string('profit_margin');
            $table->string('product_price');
            $table->string('tips');
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
        Schema::dropIfExists('update_excel_exports');
    }
}
