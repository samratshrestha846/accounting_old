<?php

use App\Models\HotelCustomerType;
use App\Models\HotelTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHotelReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(HotelTable::class, 'table_id');
            $table->foreignIdFor(HotelCustomerType::class, 'customer_type_id');
            $table->foreignIdFor(\App\Models\Client::class, 'client_id');
            $table->integer('number_of_people');
            $table->timestamp('date_time_start')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('date_time_end')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('status')->comment('0 - Free, 1 - Booked, 2 - Reserved');
            $table->integer('payment_method');
            $table->integer('is_paid')->comment('0 - Not Paid, 1 - Paid');
            $table->decimal('amount', 16, 2)->nullable();
            $table->date('date_to_paid')->nullable();
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
        Schema::dropIfExists('hotel_reservations');
    }
}
