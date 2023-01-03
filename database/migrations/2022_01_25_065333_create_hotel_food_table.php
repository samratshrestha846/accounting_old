<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_foods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('food_name');
            $table->foreignId('kitchen_id')->constrained('hotel_kitchens');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('food_image')->nullable();
            $table->time('cooking_time')->nullable();
            $table->text('component')->nullable();
            $table->text('notes')->nullable();
            $table->text('description')->nullable();
            $table->decimal('food_price', 16, 2);
            $table->boolean('status');
            $table->timestamps();

            $table->index(['category_id','kitchen_id','food_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_food');
    }
}
