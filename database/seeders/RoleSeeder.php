<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'SuperAdmin']);

        // consultamos  todos los permisos
        $permissions = Permission::all();

        // Asigna todos los permisos al rol
        $admin->syncPermissions($permissions);

    }
}
