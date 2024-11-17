<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleHasPermission extends Pivot
{
    // Nama tabel (optional jika sesuai dengan konvensi)
    protected $table = 'role_has_permissions';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    // Menonaktifkan timestamps jika tabel tidak memiliki kolom created_at dan updated_at
    public $timestamps = false;

    /**
     * Relasi ke model Permission
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    /**
     * Relasi ke model Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
