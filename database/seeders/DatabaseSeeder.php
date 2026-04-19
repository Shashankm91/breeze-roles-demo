<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Roles - firstOrCreate use kar (safe hai)
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        $userRole = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web'
        ]);

        // ✅ Permissions - firstOrCreate use kar
        $viewDashboard = Permission::firstOrCreate([
            'name' => 'view dashboard',
            'guard_name' => 'web'
        ]);

        $manageUsers = Permission::firstOrCreate([
            'name' => 'manage users',
            'guard_name' => 'web'
        ]);

        // ✅ Assign permissions to roles
        $adminRole->givePermissionTo([$viewDashboard, $manageUsers]);
        $userRole->givePermissionTo($viewDashboard);

        // ✅ Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $adminUser->assignRole($adminRole);

        // ✅ Create regular user
        $regularUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => bcrypt('password'),
            ]
        );
        $regularUser->assignRole($userRole);
    }
}
