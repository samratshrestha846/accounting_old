<?php

namespace Database\Seeders;

use App\Models\Paymentmode;
use Illuminate\Database\Seeder;

class PaymentmodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Paymentmode::insert([
            [
                'payment_mode'=>'Cash',
                'status'=>'1',
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'payment_mode'=>'Cheque',
                'status'=>'1',
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'payment_mode'=>'Bank Deposit',
                'status'=>'1',
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'payment_mode'=>'Online Payment',
                'status'=>'1',
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
