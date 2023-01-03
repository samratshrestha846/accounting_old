<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('particulars');
            $table->string('narration')->nullable();
            $table->string('cheque_no')->nullable();
            $table->double('quantity',16,2)->nullable();
            $table->string('purchase_type')->nullable();
            $table->string('purchase_unit')->nullable();
            $table->float('rate', 16, 2)->nullable();
            $table->float('discountamt', 16,2)->nullable();
            $table->string('discounttype')->nullable();
            $table->float('dtamt', 16, 2)->nullable();
            $table->float('taxamt', 16,2)->nullable();
            $table->float('itemtax', 16,2)->nullable();
            $table->string('taxtype')->nullable();
            $table->string('tax')->nullable();
            $table->string('unit')->nullable();
            $table->float('total', 16, 2);
            $table->timestamps();
        });
    }

    /**€
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_extras');
    }
}
