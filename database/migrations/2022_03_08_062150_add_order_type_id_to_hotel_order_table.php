<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderTypeIdToHotelOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_orders', function (Blueprint $table) {
            $table->foreignId('order_type_id')->nullable()->constrained('hotel_order_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_orders', function (Blueprint $table) {
            $table->dropForeign(['order_type_id']);
            $table->dropColumn('order_type_id');
        });
    }
}
