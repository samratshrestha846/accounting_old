<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Tax::insert([
            [
                'title'=>'Exempt',
                'slug'=>Str::slug('Exempt'),
                'percent'=>'0',
                'is_default'=>'1',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
            [
                'title'=>'VAT',
                'slug'=>Str::slug('VAT'),
                'percent'=>'13',
                'is_default'=>'0',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
