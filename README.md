<!-- Phase 1 -->
Step 1:To install breeze package
composer require laravel/breeze --dev
step2:To install breeze blade stack
php artisan breeze:install blade
step3:to install node dependencies
npm install
step4:to run npm run dev

step5:to run php artisan migrate and show db then start laravel project
<!-- Phase 2:to install permission package -->
step6:to install permission package
composer require spatie/laravel-permission
step7:to publish migration file 
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
step 8:php artisan migrate
<!-- phase 3:to modify models and controllers files -->
step9:add these lines in user model
use Spatie\Permission\Traits\HasRoles;  // ✅ YE LINE ADD KARO
class ke andar ye line
HasRoles
step10:RegisteredUserController me ye lines add kar do
use Spatie\Permission\Models\Role;  // ✅ YE LINE ADD KARO
use Spatie\Permission\Models\Permission;  // ✅ Agar permissions bhi chahiye
store method me ye lines
 if (!Role::where('name', 'user')->exists()) {
            Role::create(['name' => 'user']);
        }
        $user->assignRole('user');

step11:iske baad databseseeder me dummy data create kr
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
        // ✅ Roles create karo - GUARD_NAME BHI DO
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'user', 'guard_name' => 'web']);

        // ✅ Permissions create karo - GUARD_NAME BHI DO
        Permission::create(['name' => 'view dashboard', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage users', 'guard_name' => 'web']);

        // ✅ Admin role ko permissions assign karo
        $adminRole = Role::findByName('admin', 'web');  // ← guard bhi specify kar
        $adminRole->givePermissionTo(['view dashboard', 'manage users']);

        // ✅ User role ko sirf view dashboard permission do
        $userRole = Role::findByName('user', 'web');  // ← guard bhi specify kar
        $userRole->givePermissionTo('view dashboard');

        // ✅ Create an admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $adminUser->assignRole('admin');

        // ✅ Create a regular user
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        $regularUser->assignRole('user');
    }
}
run cmd php artisan db:seed

step12:iske baad bootstrap/app.php me ye code page kar middleware register kar
upar ye lines aayegi
use Spatie\Permission\Middleware\RoleMiddleware;           // ✅ Add this
use Spatie\Permission\Middleware\PermissionMiddleware;     // ✅ Add this
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware; /

middleware function ke andar ye lines
// ✅ YE LINES ADD KARO - Middleware Aliases Register Karne Ke Liye
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

navigation.php me
 @auth
                        @if (auth()->user()->hasRole('admin'))
                            <a href="#" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500">
                                Admin Panel (Demo)
                            </a>
                        @endif
                    @endauth
step18:
test kar
