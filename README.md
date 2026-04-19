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

step13:web.php me ye route add kar 
// ✅ ✅ ✅ ADMIN ROUTES - Sirf itna code ✅ ✅ ✅
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::get('/users', function () {
        $users = \App\Models\User::with('roles')->get();
        return view('admin.users', compact('users'));
    })->name('users');
});
step14:dashboard blade me ye code daal
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
                <p>Welcome, {{ Auth::user()->name }}!</p>
                <p>Your role: <span class="font-semibold text-blue-600">{{ Auth::user()->roles->pluck('name')->implode(', ') }}</span></p>
            </div>
        </div>
    </div>
</div>
@endsection

step15:users blade me ye code daal
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold mb-4">User Management</h1>
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Roles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">{{ $role->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
step16:navigation.blade me pahle ye dhund x-nav-link uske niche ye condition add kar
@auth
    @if(auth()->user()->hasRole('admin'))
        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
            {{ __('Admin Panel') }}
        </x-nav-link>
    @endif
@endauth
aur mobile ke liye ye dhund x-responsive-nav-link uske niche ye add kr
@auth
    @if(auth()->user()->hasRole('admin'))
        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
            {{ __('Admin Panel') }}
        </x-responsive-nav-link>
    @endif
@endauth
step18:
test kar
