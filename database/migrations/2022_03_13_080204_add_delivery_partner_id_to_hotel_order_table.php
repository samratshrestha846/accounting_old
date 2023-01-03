<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryPartnerIdToHotelOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_orders', function (Blueprint $table) {
            $table->foreignId('delivery_partner_id')->nullable()->constrained('hotel_delivery_partners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('delivery_partner_id');
            $table->dropColumn('delivery_partner_id');
        });
    }
}
