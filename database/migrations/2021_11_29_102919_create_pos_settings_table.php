<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('display_products');
            $table->unsignedBigInteger('default_category')->nullable();
            $table->unsignedBigInteger('default_customer')->nullable();
            $table->unsignedBigInteger('default_biller')->nullable();
            $table->string('default_currency')->default('Rs.');
            $table->boolean('show_tax')->default(0);
            $table->boolean('show_discount')->default(0);

            $table->foreign('default_category')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('default_customer')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('default_biller')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_settings');
    }
}
