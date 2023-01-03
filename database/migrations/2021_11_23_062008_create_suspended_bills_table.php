<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuspendedBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspended_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('customer_id')->constrained('clients');
            $table->string('tax_type')->nullable();
            $table->foreignId('tax_rate_id')->nullable()->constrained('taxes');
            $table->float('tax_value', 16 ,2)->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount_value', 16, 2)->nullable();
            $table->float('total_tax', 16, 2)->default(0);
            $table->float('total_discount', 16, 2)->default(0);
            $table->float('sub_total', 16, 2)->default(0);
            $table->float('total_cost', 16, 2)->default(0);
            $table->foreignId('suspended_by')->nullable()->constrained('users');
            $table->boolean('is_canceled')->default(false);
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
        Schema::dropIfExists('suspended_bills');
    }
}
