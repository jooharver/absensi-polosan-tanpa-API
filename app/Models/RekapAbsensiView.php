<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapAbsensiView extends Model
{
    protected $table = 'view_rekap_absensi'; // Nama view
    public $timestamps = false; // View tidak memiliki kolom timestamps

    protected $primaryKey = 'karyawan_id'; // Set primary key
    public $incrementing = false; // Karena bukan auto-increment
    protected $keyType = 'string'; // Tipe data primary key

    protected $fillable = [
        'karyawan_id',
        'total_hadir',
        'total_sakit',
        'total_izin',
        'total_alpha',
    ];

    // Method untuk mengubah menit ke format jam:menit
    public function getFormattedDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $minutes); // Format H:i
    }

        // Relasi dengan model Karyawan
        public function karyawan()
        {
            return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan'); // Asumsi karyawan_id adalah kolom relasi
        }
}
