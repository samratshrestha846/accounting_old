<?php

use App\Models\HotelTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            // $table->foreignId('table_id')->nullable()->constrained('hotel_tables');
            $table->foreignId('customer_id')->constrained('clients');
            $table->dateTime('order_at');
            $table->integer('total_items');
            $table->string('tax_type')->nullable();
            $table->foreignId('tax_rate_id')->nullable()->constrained('taxes');
            $table->float('tax_value', 16 ,2)->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount_value', 16, 2)->nullable();
            $table->float('total_tax', 16, 2)->default(0);
            $table->float('total_discount', 16, 2)->default(0);
            $table->float('sub_total', 16, 2)->default(0);
            $table->float('total_cost', 16, 2)->default(0);
            $table->integer('status')->comment('0 - Cancled, 1- Pending, 2 - Ready, 3 - Served');
            $table->foreignId('waiter_id')->constrained('users');
            $table->timestamps();

            $table->index(['order_at','status']);
        });

        // if (Schema::hasColumn('table_id'))
        // {
        //     Schema::table('hotel_orders', function (Blueprint $table)
        //     {
        //         $table->dropColumn('table_id');
        //     });
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_orders');
    }
}
