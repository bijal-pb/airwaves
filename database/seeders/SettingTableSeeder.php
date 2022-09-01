<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = Setting::create([
            'name' => 'Laravel Quick Demo', 
            'url' => 'http://localhost',
            'env' => 'local',
            'debug' => 'true',
            'fcm' => '',
        ]);
    }
}
