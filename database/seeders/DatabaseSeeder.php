<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Ask for db migration refresh, default is no
        if ($this->command->confirm('Do you wish to refresh migration before seeding, it will clear all old data ?')) {
            // disable fk constrain check
            // \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Call the php artisan migrate:refresh
            $this->command->call('migrate:refresh');
            $this->command->warn("Data cleared, starting from blank database.");

            // enable back fk constrain check
            // \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
        $this->call(PermissionSeeder::class);
        $this->call(SettingSeeder::class);
        $this->command->info('Settings, Permission, Role, Seeding Done.');
        $this->command->info('Here is your System Admin details to login: e-mail: systemadmin@gmail.com , password: 123456');

    }
}

//php artisan db:seed --class=DatabaseSeeder
