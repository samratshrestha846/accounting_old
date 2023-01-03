<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('food_id')->constrained('hotel_foods');
            $table->foreignId('order_id')->constrained('hotel_orders');
            $table->string('food_name');
            $table->integer('quantity');
            $table->float('unit_price', 16, 2)->comment('cost price of the food');
            $table->string('tax_type')->nullable();
            $table->foreignId('tax_rate_id')->nullable()->constrained('taxes');
            $table->float('tax_value', 16, 2)->nullable()->comment('value of tax rate');
            $table->string('discount_type')->nullable();
            $table->float('discount_value', 16, 2)->nullable()->comment('value of discount type');
            $table->float('total_tax', 16, 2)->comment('total tax of all food quantity unit');
            $table->float('total_discount', 16, 2)->comment('total discount of all food quantity unit');
            $table->float('sub_total', 16, 2)->comment('sub total of all food quantity unit without tax and discount');
            $table->float('total_cost', 16, 2)->comment('total cost of all food quantity unit with tax and discount');
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
        Schema::dropIfExists('hotel_order_items');
    }
}
