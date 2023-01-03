<?php

namespace Database\Seeders;

use App\Models\SuperSetting;
use Illuminate\Database\Seeder;

class SuperSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SuperSetting::insert([
            [
                'user_limit' => 4,
                'company_limit' => 1,
                'branch_limit' => 1,
                'expire_date' => '2022-12-30 0:00:00',
                'attendance' => 0,
                'notified' => 0,
                'journal_edit_number' => 1,
                'journal_edit_days_limit' => 100,
                'allocated_days' => 20,
                'allocated_bills' => 3,
                'allocated_amount' => 20000,
            ],
        ]);
    }
}
