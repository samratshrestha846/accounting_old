<?php

namespace Database\Seeders;

use App\Models\Billingtype;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BillingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Billingtype::insert([
            [
                'billing_types'=>'Sales',
                'slug'=>Str::slug('Sales'),
                'status'=>1,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'billing_types'=>'Purchase',
                'slug'=>Str::slug('Purchase'),
                'status'=>1,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'billing_types'=>'Receipt',
                'slug'=>Str::slug('Receipt'),
                'status'=>1,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'billing_types'=>'Payment',
                'slug'=>Str::slug('Payment'),
                'status'=>1,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'billing_types'=>'Debit Note',
                'slug'=>Str::slug('Debit Note'),
                'status'=>1,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'billing_types'=>'Credit Note',
                'slug'=>Str::slug('Credit Note'),
                'status'=>1,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'billing_types'=>'Quotation',
                'slug'=>Str::slug('Quotation'),
                'status'=>1,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'billing_types'=>'Hotel Pos',
                'slug'=>Str::slug('Hotel Pos'),
                'status'=>1,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
