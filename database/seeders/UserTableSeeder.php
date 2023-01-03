<?php

namespace Database\Seeders;

use App\Helpers\HashPinNumber;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::insert([
            [
                "name"=>"Nectar Digit",
                "email"=>"lekhabidhi@gmail.com",
                "password"=>Hash::make("N#C&@r*92901$%"),
                "created_at"=>date('Y-m-d H:i:s'),
                "updated_at"=>date('Y-m-d H:i:s'),
                'pin_number' => (new HashPinNumber)->make(1111),
            ],
            [
                "name"=>"Mr.Admin",
                "email"=>"admin@admin.com",
                "password"=>Hash::make("password"),
                "created_at"=>date('Y-m-d H:i:s'),
                "updated_at"=>date('Y-m-d H:i:s'),
                'pin_number' => (new HashPinNumber)->make(1111),
            ]
        ]);

        UserCompany::insert([
            [
                'user_id' => 1,
                'company_id' => 1,
                'branch_id'=> 1,
                'is_selected'=>1,
                "created_at"=>date('Y-m-d H:i:s'),
                "updated_at"=>date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 2,
                'company_id' => 1,
                'branch_id'=> 1,
                'is_selected'=>1,
                "created_at"=>date('Y-m-d H:i:s'),
                "updated_at"=>date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
