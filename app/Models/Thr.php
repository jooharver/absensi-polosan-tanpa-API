<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Posisi;
use App\Models\SetThr;
use Illuminate\Support\Facades\DB;

class THR extends Model
{
    protected $table = 'thrs';
    protected $primaryKey = 'id_thr'; // Set primary key menjadi id_thr
    protected $fillable = ['karyawan_id', 'thr'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'from' => null, // Karena ini adalah penambahan
                'to' => json_encode($model->getAttributes()),
            ]);
        });

        static::updated(function ($model) {
            $changes = $model->getChanges(); // Mendapatkan atribut yang diubah
            $original = $model->getOriginal(); // Mendapatkan nilai asli

            // Variabel untuk menyimpan data 'from' dan 'to' dengan tambahan kolom id_karyawan dan nama
            $from = [
                'id_thr' => $original['id_thr'],
            ];
            $to = [
                'id_thr' => $original['id_thr'],
            ];

            foreach ($changes as $key => $value) {
                // Mengabaikan kolom 'tanggal_masuk' dan kolom timestamp
                if ($key !== 'tanggal_masuk' && !in_array($key, ['created_at', 'updated_at'])) {
                    // Menyimpan atribut yang diubah
                    $from[$key] = $original[$key];
                    $to[$key] = $value;
                }
            }

            if (!empty($from) || !empty($to)) { // Pastikan hanya mencatat jika ada perubahan yang relevan
                AdminActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'update',
                    'from' => json_encode($from), // Hanya atribut yang diubah
                    'to' => json_encode($to), // Hanya atribut yang diubah
                ]);
            }
        });

        static::deleted(function ($model) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'from' => json_encode($model->getAttributes()), // Menyimpan semua data saat dihapus
                'to' => null, // Karena data ini dihapus
            ]);
        });
    }

    public static function updateTHRForPosisi($posisiId)
    {
        // Ambil semua karyawan dengan posisi_id yang diberikan
        $karyawans = Karyawan::where('posisi_id', $posisiId)->get();

        foreach ($karyawans as $karyawan) {
            self::calculateAndSaveTHR($karyawan->id_karyawan);
        }
    }

    public static function removeTHRForPosisi($posisiId)
    {
        // Ambil semua karyawan dengan posisi_id yang diberikan
        $karyawans = Karyawan::where('posisi_id', $posisiId)->get();

        foreach ($karyawans as $karyawan) {
            // Set THR menjadi 0 atau hapus data THR jika diinginkan
            self::updateOrCreate(
                ['karyawan_id' => $karyawan->id_karyawan],
                ['thr' => 0] // Atau bisa hapus menggunakan delete()
            );
        }
    }

    public static function calculateAndSaveTHR($karyawanId)
    {
        // 1. Ambil durasi kerja karyawan dari tabel Absensi
        $totalDurasiKerja = Absensi::where('karyawan_id', $karyawanId)
            ->sum(DB::raw('TIMESTAMPDIFF(HOUR, jam_masuk, jam_keluar)'));

        // 2. Ambil data karyawan dan pastikan tidak null
        $karyawan = Karyawan::find($karyawanId);
        if (!$karyawan) {
            // Return early if karyawan not found
            return;
        }

        // 3. Ambil data posisi karyawan
        $posisi = Posisi::find($karyawan->posisi_id);
        if (!$posisi) {
            // Return early if posisi not found
            return;
        }

        // Mengambil jam kerja per hari dan hari kerja per minggu dari posisi
        $jamKerjaPerHari = $posisi->jam_kerja_per_hari;
        $hariKerjaPerMinggu = $posisi->hari_kerja_per_minggu;

        // 4. Hitung total durasi kerja yang dibutuhkan per tahun
        $totalDurasiKerjaDibutuhkan = $jamKerjaPerHari * $hariKerjaPerMinggu * 52;

        // 5. Ambil besaran THR dari tabel SetThr sesuai posisi
        $besaranTHR = SetThr::where('posisi_id', $posisi->id_posisi)->value('besaran_thr');

        // 6. Hitung THR berdasarkan proporsi durasi kerja aktual dan yang dibutuhkan
        $thr = $totalDurasiKerjaDibutuhkan > 0 ? ($besaranTHR * $totalDurasiKerja) / $totalDurasiKerjaDibutuhkan : 0;

        // 7. Simpan nilai THR di tabel THRs
        THR::updateOrCreate(
            ['karyawan_id' => $karyawanId], // Kunci pencarian
            ['thr' => $thr]                  // Data yang ingin diupdate atau disimpan
        );
    }

}
