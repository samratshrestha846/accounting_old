<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousePosTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_pos_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('godown_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('outlet_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->date('transfer_eng_date');
            $table->string('transfer_nep_date');
            $table->unsignedBigInteger('transfered_by');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('warehouse_pos_transfers');
    }
}
