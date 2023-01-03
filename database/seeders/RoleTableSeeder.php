<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                "name" => "Super Admin",
                "slug" => Str::slug("Super Admin"),
            ],
            [
                "name" => "Admin",
                "slug" => Str::slug("Admin"),
            ],
            [
                "name"=>"Biller",
                "slug"=>Str::slug("Biller"),
            ],
            [
                "name" => "Hospital Staff",
                "slug" => Str::slug("Hospital Staff"),
            ]
        ];

        foreach ($items as $item) {
            Role::updateOrCreate($item);
        }
    }
}
