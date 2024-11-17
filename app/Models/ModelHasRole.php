<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    // Tentukan nama tabel jika tidak mengikuti konvensi Laravel
    protected $table = 'model_has_roles';

    // Atur kolom yang dapat diisi secara massal
    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];

    // Nonaktifkan timestamps jika tabel tidak memiliki kolom created_at dan updated_at
    public $timestamps = false;

    /**
     * Relasi dengan model Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

}
