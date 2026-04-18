<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //
        $permissions = [
            // Utilisateur
            'view-stations',
            'add-favorite',
            'delete-favorite',
            'create-review',
            'manage-alerts',
            'view-history',

            // Opérateur
            'update-connector-status',
            'view-own-station-stats',

            // Admin
            'manage-stations',
            'manage-users',
            'manage-reviews',
            'view-global-stats',
            'view-logs',
            'assign-roles',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view-stations',
            'add-favorite',
            'delete-favorite',
            'create-review',
            'manage-alerts',
            'view-history',
        ]);


        $operatorRole = Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $operatorRole->syncPermissions([
            'view-stations',
            'update-connector-status',
            'view-own-station-stats',
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());
    }
}
