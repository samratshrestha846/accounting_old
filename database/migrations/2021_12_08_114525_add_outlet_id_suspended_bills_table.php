<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutletIdSuspendedBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suspended_bills', function (Blueprint $table) {
            $table->foreignId('outlet_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suspended_bills', function (Blueprint $table) {
            //
        });
    }
}
