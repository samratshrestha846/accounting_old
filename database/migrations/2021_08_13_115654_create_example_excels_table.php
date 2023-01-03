<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExampleExcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('example_excels', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_code');
            $table->string('category');
            $table->string('size');
            $table->string('color');
            $table->string('serial_numbers')->nullable();
            $table->string('total_stock');
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
            $table->longText('description');
            $table->string('status');
            $table->string('primary_number');
            $table->string('primary_unit');
            $table->string('secondary_number');
            $table->string('secondary_unit');
            $table->string('supplier');
            $table->string('brand');
            $table->string('series');
            $table->string('refundable');
            $table->longText('godowns');
            $table->string('stock_by_godown');
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
        Schema::dropIfExists('example_excels');
    }
}
