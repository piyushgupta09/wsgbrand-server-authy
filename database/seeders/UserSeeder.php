<?php

namespace Fpaipl\Authy\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('panel.roles') as $role) {
            Role::create([
                'name' => $role['id'],
                'guard_name' => 'web',
            ]);
        }

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'pg.softcode@gmail.com',
            'mobile' => '9868252588',
            'utype' => 'mobile',
            'type' => 'admin',
            'password' => bcrypt('password1245'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('admin');
        $admin->update();

        $admin = User::create([
            'name' => 'Ayush Gupta',
            'email' => 'apptest@wsgbrand.in',
            'mobile' => '8860012001',
            'utype' => 'mobile',
            'type' => 'admin',
            'password' => bcrypt('987654321'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('owner-brand');
        $admin->assignRole('manager-brand');
        $admin->update();

    }
}
