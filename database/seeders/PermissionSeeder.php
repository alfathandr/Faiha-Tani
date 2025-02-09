<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat peran (roles)
        $role_admin = Role::updateOrCreate(['name' => 'admin']);
        $role_konsumen = Role::updateOrCreate(['name' => 'konsumen']);

        // Membuat izin (permissions)
        $permission_manage_users = Permission::updateOrCreate(['name' => 'manage_users']);
        $permission_view_products = Permission::updateOrCreate(['name' => 'view_products']);

        // Menetapkan izin ke peran
        $role_admin->givePermissionTo($permission_manage_users);
        $role_konsumen->givePermissionTo($permission_view_products);
    }
}

