<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotationsettings', function (Blueprint $table) {
            $table->id();
            $table->boolean('show_brand')->default(0);
            $table->boolean('show_model')->default(0);
            $table->boolean('show_picture')->default(0);
            $table->string('letterhead');
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
        Schema::dropIfExists('quotationsettings');
    }
}
