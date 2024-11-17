<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    use HasFactory;
    // Nama tabel (optional jika sesuai dengan konvensi)
    // protected $table = 'permissions';

    // // Kolom yang dapat diisi secara massal
    // protected $fillable = [
    //     'name',
    //     'guard_name',
    // ];

    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'role_has_permissions');
    // }

    // /**
    //  * Relasi polimorfik banyak ke banyak dengan model lainnya
    //  */
    // public function models()
    // {
    //     return $this->morphedByMany(Model::class, 'model', 'model_has_permissions');
    // }
}
