<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\THR;

class ViewAbsen extends Model
{
    use HasFactory;

    protected $table = 'view_absen';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'id_absen';

    protected $fillable = [
        'id_absen',
        'tanggal',
        'karyawan_id',
        'jam_masuk',
        'jam_keluar',
        'alpha',
        'hadir',
        'sakit',
        'izin',
        'keterangan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($absen) {
            THR::calculateAndSaveTHR($absen->karyawan_id);
        });

        static::deleted(function ($absen) {
            THR::calculateAndSaveTHR($absen->karyawan_id);
        });
    }
}
