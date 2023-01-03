<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Branch::insert([
            [
                'company_id'=>1,
                'name'=>'Nectar Accounting',
                'email'=>'nectardigit@gmail.com',
                'phone'=>'9818505218',
                'province_no'=>1,
                'district_no'=>2,
                'local_address'=>'Putalisadak',
                'is_headoffice'=>1,
            ]
        ]);
    }
}
