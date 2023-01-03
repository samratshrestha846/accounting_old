<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemainingPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("roles_permissions")->insert([
            [
                "role_id" => "1",
                "permission_id" => "103",
            ],
            [
                "role_id" => "1",
                "permission_id" => "104",
            ],
            [
                "role_id" => "1",
                "permission_id" => "105",
            ],
            [
                "role_id" => "1",
                "permission_id" => "106",
            ],
            [
                "role_id" => "1",
                "permission_id" => "107",
            ],
            [
                "role_id" => "1",
                "permission_id" => "108",
            ],
            [
                "role_id" => "1",
                "permission_id" => "109",
            ],
            [
                "role_id" => "1",
                "permission_id" => "110",
            ],
            [
                "role_id" => "1",
                "permission_id" => "111",
            ],
            [
                "role_id" => "1",
                "permission_id" => "112",
            ],
            [
                "role_id" => "1",
                "permission_id" => "113",
            ],
            [
                "role_id" => "1",
                "permission_id" => "114",
            ],
            [
                "role_id" => "1",
                "permission_id" => "115",
            ],
            [
                "role_id" => "1",
                "permission_id" => "116",
            ],
            [
                "role_id" => "1",
                "permission_id" => "117",
            ],
            [
                "role_id" => "1",
                "permission_id" => "118",
            ],
            [
                "role_id" => "1",
                "permission_id" => "119",
            ],
            [
                "role_id" => "1",
                "permission_id" => "120",
            ],
            [
                "role_id" => "1",
                "permission_id" => "121",
            ],
            [
                "role_id" => "1",
                "permission_id" => "122",
            ],
            [
                "role_id" => "1",
                "permission_id" => "123",
            ],
            [
                "role_id" => "1",
                "permission_id" => "124",
            ],
            [
                "role_id" => "1",
                "permission_id" => "125",
            ],
            [
                "role_id" => "1",
                "permission_id" => "126",
            ],
        ]);
    }
}
