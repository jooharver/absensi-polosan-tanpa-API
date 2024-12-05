<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'izin';

    // Primary Key
    protected $primaryKey = 'id_izin';

    // Kolom yang dapat diisi
    protected $fillable = [
        'karyawan_id',
        'jenis_izin',
        'keterangan',
        'foto',
        'status',
    ];

    /**
     * Relasi ke model Karyawan.
     * Menghubungkan karyawan_id dengan id_karyawan pada tabel karyawans.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }
}
