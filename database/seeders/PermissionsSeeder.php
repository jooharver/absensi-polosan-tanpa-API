<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'View Posts', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'Create Posts', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'Edit Posts', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'Delete Posts', 'guard_name' => 'web']);
    }
}
