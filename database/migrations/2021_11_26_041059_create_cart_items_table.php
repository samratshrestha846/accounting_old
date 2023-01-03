<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->string('product_name');
            $table->string('product_code');
            $table->float('product_price', 16 ,2);
            $table->integer('quantity');
            $table->string('tax_type')->nullable();
            $table->foreignId('tax_rate_id')->nullable()->constrained('taxes');
            $table->float('tax_value', 16, 2)->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount_value', 16 ,2)->nullable();
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
        Schema::dropIfExists('cart_items');
    }
}
