<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Absen;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Izin extends Model
{
    use HasFactory;

    protected $table = 'izins';
    protected $primaryKey = 'id_izin';

    protected $fillable = [
        'karyawan_id',
        'jenis',
        'start',
        'end',
        'status',
        'keterangan',
        'image_path'
    ];


    protected static function booted()
    {
        parent::booted();

        // Event 'updated' pada model Izin
        static::updated(function ($izin) {
            // Pastikan status izin diubah menjadi "approved"
            if ($izin->status == 'approved') {
                // Pastikan start dan end diubah menjadi objek Carbon
                $start = Carbon::parse($izin->start);
                $end = Carbon::parse($izin->end);

                // Hitung jumlah hari izin (termasuk hari pertama dan terakhir)
                $totalDays = $end->diffInDays($start) + 1;

                // Ambil jam kerja per hari dari tabel karyawan
                $jamKerjaPerHari = DB::table('karyawans')
                    ->join('posisis', 'karyawans.posisi_id', '=', 'posisis.id_posisi')
                    ->where('karyawans.id_karyawan', $izin->karyawan_id)
                    ->value('jam_kerja_per_hari');

                // Hitung total jam kerja untuk izin (dalam jam)
                $totalJam = $jamKerjaPerHari * $totalDays;

                // Konversi total jam ke format TIME (HH:MM:SS)
                $hours = floor($totalJam);
                $minutes = ($totalJam - $hours) * 60;
                $seconds = 0; // karena kita hanya menghitung jam dan menit, detik adalah 0

                // Formatkan total jam kerja ke TIME
                $totalJamTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                // Sisipkan data absensi ke tabel absen
                DB::table('absen')->insert([
                    'karyawan_id' => $izin->karyawan_id,
                    'tanggal' => now()->toDateString(), // tanggal sekarang
                    'hadir' => '00:00:00',  // Kolom hadir terisi dengan 00:00:00
                    'jam_masuk' => null,  // Kolom jam_masuk terisi NULL
                    'jam_keluar' => null,  // Kolom jam_keluar terisi NULL
                    'izin' => $izin->jenis == 'izin' ? $totalJamTime : '00:00:00',
                    'sakit' => $izin->jenis == 'sakit' ? $totalJamTime : '00:00:00',
                    'keterangan' => "Izin jenis {$izin->jenis} selama {$totalDays} hari",
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }

}
