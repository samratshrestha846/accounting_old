<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Company::insert([
            [
                'name'=>'Nectar Accounting',
                'email'=>'nectardigit@gmail.com',
                'phone'=>'9818505218',
                'province_no'=>1,
                'district_no'=>2,
                'local_address'=>'Putalisadak',
                'registration_no'=>'56446565',
                'ird_sync'=>1,
                'pan_vat'=>'654632',
                'company_logo'=>'logo.png',
            ]
        ]);
    }
}
