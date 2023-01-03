<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_infos', function (Blueprint $table) {
            $table->id();
            $table->string('fiscal_year');
            $table->string('nep_month');
            $table->float('purchase_tax',16,2)->nullable()->default(null);
            $table->float('sales_tax',16,2)->nullable()->default(null);
            $table->float('purchasereturn_tax',16,2)->nullable()->default(null);
            $table->float('salesreturn_tax',16,2)->nullable()->default(null);
            $table->float('total_tax');
            $table->boolean('is_paid');
            $table->float('due', 16,2)->nullable()->default(null);
            $table->string('paid_at')->nullable()->default(null);
            $table->string('file')->nullable()->default(null);
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
        Schema::dropIfExists('tax_infos');
    }
}
