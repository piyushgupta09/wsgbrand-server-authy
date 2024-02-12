<?php

namespace Fpaipl\Authy\Database\Seeders;

use Illuminate\Database\Seeder;
use Fpaipl\Authy\Database\Seeders\UserSeeder;

class AuthyDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
    */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
}
