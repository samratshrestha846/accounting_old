<?php

use App\Models\HotelFloor;
use App\Models\HotelRoom;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('floor_id')->constrained('hotel_floors');
            $table->foreignId('room_id')->constrained('hotel_rooms');
            $table->string('name');
            $table->string('code');
            $table->integer('max_capacity');
            $table->boolean('is_cabin');
            $table->foreignId('cabin_type_id')->nullable()->constrained('cabin_types');
            $table->decimal('cabin_charge', 16, 2)->nullable();
            $table->timestamps();

            $table->index(['name','code','max_capacity']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_tables');
    }
}
