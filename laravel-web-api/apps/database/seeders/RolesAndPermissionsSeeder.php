<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            // User Management
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            // Role Management
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',
            'role.assign-permissions',

            // Permission Management
            'permission.view',
            'permission.create',
            'permission.edit',
            'permission.delete',

            // Dashboard
            'dashboard.access',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles
        $roles = [
            'super-admin',
            'admin',
            'students',
            'teacher',
            'staff',
            'accounts',
            'guest',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Assign all permissions to super-admin
        $superAdmin = Role::findByName('super-admin');
        $superAdmin->syncPermissions(Permission::all());

        // Assign permissions to admin
        $admin = Role::findByName('admin');
        $admin->syncPermissions([
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'role.view',
            'role.assign-permissions',
            'permission.view',
            'permission.create',
            'permission.edit',
            'dashboard.access',
        ]);

        // Assign limited permissions to teacher
        $teacher = Role::findByName('teacher');
        $teacher->syncPermissions([
            'user.view',
            'dashboard.access',
        ]);

        // Assign limited permissions to staff
        $staff = Role::findByName('staff');
        $staff->syncPermissions([
            'user.view',
            'dashboard.access',
        ]);

        // Assign limited permissions to accounts
        $accounts = Role::findByName('accounts');
        $accounts->syncPermissions([
            'user.view',
            'dashboard.access',
        ]);

        // Assign limited permissions to students
        $students = Role::findByName('students');
        $students->syncPermissions([
            'dashboard.access',
        ]);

        // Assign limited permissions to guest
        $guest = Role::findByName('guest');
        $guest->syncPermissions([
            'dashboard.access',
        ]);
    }
}
