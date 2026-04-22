<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([RolesAndPermissionsSeeder::class]);

        $admin = User::firstOrCreate(
            [
                'email' => 'admin@electrocharge.ma'
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin@2024'),
            ]
        );
        $admin->assignRole('admin');
        // $admin->assignRole('admin');
    }
}
