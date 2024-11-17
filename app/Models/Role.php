<?php

namespace App\Models;

use Database\Seeders\RoleHasPermissionSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasFactory;
    // protected $fillable = [
    //     'name',
    //     'guard_name',
    // ];

    // public function roles()
    // {
    //     return $this->hasMany(ModelHasRole::class, 'role_id', 'id');
    // }
}
