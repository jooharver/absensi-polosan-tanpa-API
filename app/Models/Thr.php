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
        // 1. Hitung total durasi hadir, sakit, dan izin (dalam menit)
        $totalDurasi = DB::table('absen')
            ->where('karyawan_id', $karyawanId)
            ->selectRaw("
                SUM(TIME_TO_SEC(hadir)) / 60 as total_hadir,
                SUM(TIME_TO_SEC(sakit)) / 60 as total_sakit,
                SUM(TIME_TO_SEC(izin)) / 60 as total_izin,
                SUM(TIME_TO_SEC(alpha)) / 60 as total_alpha
            ")
            ->first();

        if (!$totalDurasi || ($totalDurasi->total_hadir + $totalDurasi->total_sakit + $totalDurasi->total_izin) == 0) {
            // Jika tidak ada data, kembalikan lebih awal
            return;
        }

        // Total durasi kerja aktual (dalam menit)
        $totalDurasiKerja = $totalDurasi->total_hadir + $totalDurasi->total_sakit + $totalDurasi->total_izin;

        // 2. Ambil data karyawan dan pastikan tidak null
        $karyawan = Karyawan::find($karyawanId);
        if (!$karyawan) {
            return;
        }

        // 3. Ambil data posisi karyawan
        $posisi = Posisi::find($karyawan->posisi_id);
        if (!$posisi) {
            return;
        }

        // Mengambil jam kerja per hari dan hari kerja per minggu dari posisi
        $jamKerjaPerHari = $posisi->jam_kerja_per_hari;
        $hariKerjaPerMinggu = $posisi->hari_kerja_per_minggu;

        // 4. Hitung total durasi kerja yang dibutuhkan per tahun (dalam menit)
        $totalDurasiKerjaDibutuhkan = $jamKerjaPerHari * 60 * $hariKerjaPerMinggu * 52;

        // 5. Ambil besaran THR dari tabel SetThr sesuai posisi
        $besaranTHR = SetThr::where('posisi_id', $posisi->id_posisi)->value('besaran_thr');

        // 6. Hitung THR berdasarkan proporsi durasi kerja aktual dan yang dibutuhkan
        $thr = $totalDurasiKerjaDibutuhkan > 0
            ? ($besaranTHR * $totalDurasiKerja) / $totalDurasiKerjaDibutuhkan
            : 0;

        // 7. Simpan nilai THR di tabel THRs
        THR::updateOrCreate(
            ['karyawan_id' => $karyawanId], // Kunci pencarian
            ['thr' => $thr]                 // Data yang ingin diupdate atau disimpan
        );
    }



}
