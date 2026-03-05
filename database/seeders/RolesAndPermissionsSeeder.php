<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Seed roles and permissions for RBAC (Role-Based Access Control).
     * Creates permissions for article management and assigns them to writer and admin roles.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $edit = Permission::firstOrCreate(['name' => 'edit articles']);
        $delete = Permission::firstOrCreate(['name' => 'delete articles']);
        $publish = Permission::firstOrCreate(['name' => 'publish articles']);
        $unpublish = Permission::firstOrCreate(['name' => 'unpublish articles']);

        $writer = Role::firstOrCreate(['name' => 'writer']);
        $admin = Role::firstOrCreate(['name' => 'admin']);

        $writer->syncPermissions([$edit]);
        $admin->syncPermissions([$edit, $delete, $publish, $unpublish]);
    }
}
