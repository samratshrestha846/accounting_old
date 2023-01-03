<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFloorIdAndRoomIdToHotelOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_orders', function (Blueprint $table) {
            // $table->foreignId('floor_id')->nullable()->constrained('hotel_floors');
            // $table->foreignId('room_id')->nullable()->constrained('hotel_rooms');
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
            //
        });
    }
}
