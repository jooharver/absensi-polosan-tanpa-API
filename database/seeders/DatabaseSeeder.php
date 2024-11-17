<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PosisiSeeder::class,
            KaryawanSeeder::class,
            AbsensiSeeder::class,
            SetThrSeeder::class,
            AdminActivityLogSeeder::class,
            // RoleSeeder::class,
            // ModelHasRoleSeeder::class,
            // PermissionSeeder::class,
            // RoleHasPermissionSeeder::class,

        ]);

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin')
        ]);
        $role = Role::create(['name' => 'Admin']);
        $user->assignRole($role);
        // $permission = Permission::create(['name' => 'edit articles']);
    }
}
