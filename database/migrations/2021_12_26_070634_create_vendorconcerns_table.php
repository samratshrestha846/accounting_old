<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorconcernsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendorconcerns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('concerned_name')->nullable()->default(null);
            $table->string('concerned_phone')->nullable()->default(null);
            $table->string('concerned_email')->nullable()->default(null);
            $table->string('designation')->nullable()->default(null);
            $table->boolean('default');
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
        Schema::dropIfExists('vendorconcerns');
    }
}
