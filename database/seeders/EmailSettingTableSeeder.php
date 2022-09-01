<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailSetting;

class EmailSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email_setting = EmailSetting::create([
            'host' => 'smtp.gmail.com', 
            'port' => 587,
            'email' => 'test.ingeniousmindslab@gmail.com',
            'password' => 'test@987654321',
            'from_address' => 'test@gmail.com',
            'from_name' => 'test',
            'encryption' => 'tls',
        ]);
    }
}
