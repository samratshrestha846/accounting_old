<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('product_name');
            $table->string('product_code');
            $table->foreignId('category_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->double('opening_stock',16,2);
            $table->double('total_stock',16,2);
            $table->float('original_vendor_price');
            $table->float('charging_rate')->nullable();
            $table->float('final_vendor_price');
            $table->float('carrying_cost')->nullable();
            $table->float('transportation_cost')->nullable();
            $table->float('miscellaneous_percent')->nullable();
            $table->float('other_cost')->nullable();
            $table->float('cost_of_product');
            $table->float('custom_duty')->nullable();
            $table->string('after_custom');
            $table->float('tax')->nullable();
            $table->float('total_cost');
            $table->string('margin_type')->nullable();
            $table->float('margin_value')->nullable();
            $table->float('profit_margin');
            $table->float('product_price');
            $table->longText('description')->nullable();
            $table->boolean('status')->default(0);
            $table->string('primary_number');
            $table->string('primary_unit');
            $table->foreignId('primary_unit_id')->constrained('units');
            $table->string('primary_unit_code');
            $table->string('secondary_number')->nullable();
            $table->string('secondary_unit')->nullable();
            $table->integer('secondary_unit_id')->constrained('units')->nullable();
            $table->string('secondary_unit_code');
            $table->integer('supplier_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('series_id')->nullable();
            $table->integer('refundable');
            $table->float('secondary_unit_selling_price')->nullable();
            $table->date('expiry_date')->nullable()->default(null);
            $table->text('selected_filter_option')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['product_name', 'product_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
