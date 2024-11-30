<?php

namespace App\Models;

use App\Models\Posisi;
use App\Models\SetThr;
use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

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
        // 1. Ambil data dari ViewAbsen berdasarkan karyawan_id
        $viewAbsens = ViewAbsen::where('karyawan_id', $karyawanId)->get();

        if ($viewAbsens->isEmpty()) {
            // Jika tidak ada data di ViewAbsen, kembalikan lebih awal
            return;
        }

        // 2. Hitung total durasi hadir, sakit, dan izin
        $totalDurasiHadir = $viewAbsens->sum(function ($item) {
            return Carbon::parse($item->hadir)->hour;
        });

        $totalDurasiSakit = $viewAbsens->sum(function ($item) {
            return Carbon::parse($item->sakit)->hour;
        });

        $totalDurasiIzin = $viewAbsens->sum(function ($item) {
            return Carbon::parse($item->izin)->hour;
        });

        // Total durasi yang diperhitungkan untuk THR
        $totalDurasiKerja = $totalDurasiHadir + $totalDurasiSakit + $totalDurasiIzin;

        // 3. Ambil data karyawan dan pastikan tidak null
        $karyawan = Karyawan::find($karyawanId);
        if (!$karyawan) {
            return;
        }

        // 4. Ambil data posisi karyawan
        $posisi = Posisi::find($karyawan->posisi_id);
        if (!$posisi) {
            return;
        }

        // Mengambil jam kerja per hari dan hari kerja per minggu dari posisi
        $jamKerjaPerHari = $posisi->jam_kerja_per_hari;
        $hariKerjaPerMinggu = $posisi->hari_kerja_per_minggu;

        // 5. Hitung total durasi kerja yang dibutuhkan per tahun
        $totalDurasiKerjaDibutuhkan = $jamKerjaPerHari * $hariKerjaPerMinggu * 52;

        // 6. Ambil besaran THR dari tabel SetThr sesuai posisi
        $besaranTHR = SetThr::where('posisi_id', $posisi->id_posisi)->value('besaran_thr');

        // 7. Hitung THR berdasarkan proporsi durasi kerja aktual dan yang dibutuhkan
        $thr = $totalDurasiKerjaDibutuhkan > 0 ? ($besaranTHR * $totalDurasiKerja) / $totalDurasiKerjaDibutuhkan : 0;

        // 8. Simpan nilai THR di tabel THRs
        THR::updateOrCreate(
            ['karyawan_id' => $karyawanId], // Kunci pencarian
            ['thr' => $thr]                 // Data yang ingin diupdate atau disimpan
        );
    }

}
