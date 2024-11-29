<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenKeluar extends Model
{
    protected $table = 'absen_keluar';
    protected $primaryKey = 'id_absen_keluar';
    protected $fillable = ['jam_keluar'];


    // Relasi ke tabel absen
    public function absens()
    {
        return $this->hasMany(Absen::class, 'absen_keluar_id', 'id_absen_keluar');
    }
}

