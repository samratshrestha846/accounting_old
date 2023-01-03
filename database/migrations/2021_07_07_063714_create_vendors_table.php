<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('company_name');
            $table->string('company_email')->nullable()->default(null);
            $table->double('company_phone')->nullable()->default(null);
            $table->string('company_address')->nullable()->default(null);
            $table->integer('province_id')->nullable()->default(null);
            $table->integer('district_id')->nullable()->default(null);
            $table->string('pan_vat')->nullable()->default(null);
            $table->string('supplier_code')->unique()->nullable()->default(null);
            $table->softDeletes();
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
        Schema::dropIfExists('vendors');
    }
}
