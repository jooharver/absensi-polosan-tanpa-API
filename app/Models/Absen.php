<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absen extends Model
{
    use HasFactory;

    protected $table = 'absen'; // Nama tabel
    protected $fillable = ['karyawan_id', 'absen_masuk_id', 'absen_keluar_id', 'hadir', 'sakit', 'izin', 'alpha', 'keterangan'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($absen) {
            // Hapus data di absen_masuk jika ada
            if ($absen->absen_masuk) {
                $absen->absen_masuk->delete();
            }

            // Hapus data di absen_keluar jika ada
            if ($absen->absen_keluar) {
                $absen->absen_keluar->delete();
            }
        });

        // Perhitungan THR dilakukan tanpa pengaruh pada log admin activity
        static::saved(function ($absen) {
            Thr::calculateAndSaveTHR($absen->karyawan_id);
        });

        static::deleted(function ($absen) {
            Thr::calculateAndSaveTHR($absen->karyawan_id);
        });
    }

    public function absen_masuk()
    {
        return $this->belongsTo(AbsenMasuk::class, 'absen_masuk_id', 'id_absen_masuk');
    }

    // Relasi ke tabel absen_keluar
    public function absen_keluar()
    {
        return $this->belongsTo(AbsenKeluar::class, 'absen_keluar_id', 'id_absen_keluar');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }
}
