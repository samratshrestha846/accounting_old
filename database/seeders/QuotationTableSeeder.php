<?php

namespace Database\Seeders;

use App\Models\Quotationsetting;
use Illuminate\Database\Seeder;

class QuotationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Quotationsetting::insert([
            [
                'show_brand'=>0,
                'show_model'=>0,
                'show_picture'=>0,
                'letterhead'=>'letterhead/letterhead.png',
            ],
        ]);
    }
}
