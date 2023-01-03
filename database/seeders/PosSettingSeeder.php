<?php

namespace Database\Seeders;

use App\Models\PosSettings;
use Illuminate\Database\Seeder;

class PosSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posSetting = (new PosSettings())->createOrUpdate([
            "display_products" => 30,
            "default_category" => 1,
        ]);
    }
}
