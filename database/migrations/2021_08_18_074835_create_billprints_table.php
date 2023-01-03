<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('printed_by');
            $table->timestamp('print_time');
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
        Schema::dropIfExists('billprints');
    }
}
