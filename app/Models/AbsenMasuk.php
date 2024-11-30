<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenMasuk extends Model
{
    protected $table = 'absen_masuk';
    protected $primaryKey = 'id_absen_masuk';
    protected $fillable = ['jam_masuk'];


    // Relasi ke tabel absen
    public function absens()
    {
        return $this->hasMany(Absen::class, 'absen_masuk_id', 'id_absen_masuk');
    }
}


