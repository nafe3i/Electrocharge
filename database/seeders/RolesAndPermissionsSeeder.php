<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use spatie\Permission\Models\Permission;
use spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $prmissions = [
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
        foreach ($prmissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view-stations',
            'add-favorite',
            'delete-favorite',
            'create-review',
            'manage-alerts',
            'view-history',
        ]);


        $operatorRole = Role::create(['name' => 'operator']);
        $operatorRole->givePermissionTo([
            'view-stations',
            'update-connector-status',
            'view-own-station-stats',
        ]);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
    }
}
