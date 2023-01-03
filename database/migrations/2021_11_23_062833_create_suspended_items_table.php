<?php

use App\Enums\TaxType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuspendedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspended_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('suspended_id')->constrained('suspended_bills');
            $table->foreignId('product_id')->constrained('products');
            $table->string('product_name');
            $table->string('product_code');
            $table->integer('quantity');
            $table->float('unit_price', 16, 2)->comment('per product price');
            $table->string('tax_type')->nullable();
            $table->foreignId('tax_rate_id')->nullable()->constrained('taxes');
            $table->float('tax_value', 16, 2)->nullable()->comment('value of tax rate');
            $table->string('discount_type')->nullable();
            $table->float('discount_value', 16, 2)->nullable()->comment('value of discount type');
            $table->string('purchase_type');
            $table->string('purchase_unit');
            $table->float('total_tax', 16, 2)->comment('total tax of all product quantity unit');
            $table->float('total_discount', 16, 2)->comment('total discount of all product quantity unit');
            $table->float('sub_total', 16, 2)->comment('sub total of all product quantity unit without tax and discount');
            $table->float('total_cost', 16, 2)->comment('total cost of all product quantity unit with tax and discount');
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
        Schema::dropIfExists('suspended_items');
    }
}
