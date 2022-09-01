<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        $this->call(PermissionTableSeeder::class);
        $this->command->info("Permission Seeder Execution Completed..");
        $this->call(CreateAdminUserSeeder::class);
        $this->command->info("Admin Seeder Execution Completed..");
        $this->call(EmailSettingTableSeeder::class);
        $this->command->info("Email Setting Seeder Execution Completed..");
        $this->call(SettingTableSeeder::class);
        $this->command->info("Setting Seeder Execution Completed..");
        
    }
}
