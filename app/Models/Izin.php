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

                // Ambil keterangan dari tabel izins berdasarkan id_izin yang sedang diupdate
                $keterangan = $izin->keterangan;

                // Iterasi setiap tanggal antara start dan end
                for ($date = $start; $date <= $end; $date->addDay()) {
                    // Formatkan tanggal untuk setiap row (tanggal pada absensi)
                    $formattedDate = $date->toDateString();

                    // Sisipkan data absensi ke tabel absen
                    DB::table('absen')->insert([
                        'karyawan_id' => $izin->karyawan_id,
                        'tanggal' => $formattedDate,  // Gunakan tanggal yang diiterasi
                        'hadir' => '00:00:00',  // Kolom hadir terisi dengan 00:00:00
                        'jam_masuk' => null,  // Kolom jam_masuk terisi NULL
                        'jam_keluar' => null,  // Kolom jam_keluar terisi NULL
                        'izin' => $izin->jenis == 'izin' ? sprintf('%02d:%02d:%02d', floor($jamKerjaPerHari), ($jamKerjaPerHari - floor($jamKerjaPerHari)) * 60, 0) : '00:00:00',
                        'sakit' => $izin->jenis == 'sakit' ? sprintf('%02d:%02d:%02d', floor($jamKerjaPerHari), ($jamKerjaPerHari - floor($jamKerjaPerHari)) * 60, 0) : '00:00:00',
                        'keterangan' => $keterangan,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            // Mengambil data perubahan yang terjadi
            $changes = $izin->getDirty();
            $original = $izin->getOriginal();

            // Variabel untuk menyimpan data 'from' dan 'to' dengan tambahan kolom id_izin dan nama (dari Karyawan)
            $from = [
                'id_izin' => $original['id_izin'],
                'nama' => Karyawan::find($original['karyawan_id'])->nama, // Mengambil nama dari tabel Karyawan berdasarkan karyawan_id
            ];

            $to = [
                'id_izin' => $izin->id_izin,
                'nama' => Karyawan::find($izin->karyawan_id)->nama, // Mengambil nama dari tabel Karyawan berdasarkan karyawan_id
            ];

            // Melakukan iterasi untuk mengecek perubahan di kolom lain selain yang diabaikan (misalnya kolom tanggal atau timestamp)
            foreach ($changes as $key => $value) {
                // Mengabaikan kolom 'created_at', 'updated_at' dan kolom lain yang tidak perlu dicatat
                if ($key !== 'created_at' && $key !== 'updated_at') {
                    // Menyimpan perubahan yang relevan
                    $from[$key] = $original[$key];
                    $to[$key] = $value;
                }
            }

            // Hanya mencatat perubahan yang relevan
            if (!empty(array_diff_assoc($from, $to))) {
                AdminActivityLog::create([
                    'user_id' => auth()->id(), // ID pengguna yang melakukan perubahan
                    'action' => 'update', // Jenis aksi
                    'from' => json_encode($from), // Data sebelum perubahan
                    'to' => json_encode($to), // Data setelah perubahan
                ]);
            }
        });

        static::deleted(function ($izin) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'from' => json_encode($izin->getAttributes()), // Menyimpan semua data saat dihapus
                'to' => null, // Karena data ini dihapus
            ]);
        });
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }

}
