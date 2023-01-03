<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('outlet_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->integer('primary_stock');
            $table->integer('primary_opening_stock');
            $table->integer('primary_stock_alert');
            $table->integer('secondary_stock')->default(0);
            $table->integer('secondary_opening_stock')->default(0);
            $table->integer('secondary_stock_alert')->default(0);
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
        Schema::dropIfExists('outlet_products');
    }
}
