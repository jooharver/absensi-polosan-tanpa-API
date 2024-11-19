<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';
    protected $primaryKey = 'id_absensi';
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'absen_masuk',
        'absen_keluar',
        'hadir',
        'sakit',
        'izin',
        'alpha',
        'keterangan'
    ];

    public static function getRekapAbsensiQuery()
    {
        return self::fromSub(function ($query) {
            $query->selectRaw('
                karyawan_id,
                COUNT(CASE WHEN hadir IS NOT NULL THEN 1 END) as total_hadir,
                COUNT(CASE WHEN sakit IS NOT NULL THEN 1 END) as total_sakit,
                COUNT(CASE WHEN izin IS NOT NULL THEN 1 END) as total_izin,
                COUNT(CASE WHEN alpha IS NOT NULL THEN 1 END) as total_alpha
            ')
            ->from('absensis')
            ->groupBy('karyawan_id');
        }, 'rekap_absensi')
        ->orderBy('karyawan_id', 'asc');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->absen_masuk && $model->absen_keluar) {
                // Tambahkan detik jika input hanya HH:MM
                $absenMasuk = $model->absen_masuk . ':00';
                $absenKeluar = $model->absen_keluar . ':00';

                $start = strtotime($absenMasuk);
                $end = strtotime($absenKeluar);

                $durationInSeconds = $end - $start;

                // Periksa dan hitung durasi sakit, izin, dan alpha
                $subtractedDuration = 0;

                foreach (['sakit', 'izin', 'alpha'] as $column) {
                    if (!empty($model->{$column})) {
                        // Tambahkan detik jika input hanya HH:MM
                        $time = $model->{$column} . ':00';

                        // Hitung durasi
                        list($h, $m, $s) = array_map('intval', explode(':', $time));
                        $subtractedDuration += ($h * 3600) + ($m * 60) + $s;
                    }
                }

                // Kurangi durasi sakit/izin/alpha dari total durasi
                $finalDuration = max(0, $durationInSeconds - $subtractedDuration);

                // Ubah durasi menjadi format jam:menit
                $hours = floor($finalDuration / 3600);
                $minutes = floor(($finalDuration % 3600) / 60);

                $model->hadir = sprintf('%02d:%02d', $hours, $minutes);
            } else {
                // Jika salah satu absen kosong, set hadir ke NULL
                $model->hadir = null;
            }
        });



        // Event untuk mencatat aktivitas pembuatan data absensi
        static::created(function ($model) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'from' => null,
                'to' => json_encode([
                    'absen_masuk' => $model->absen_masuk,
                    'absen_keluar' => $model->absen_keluar,
                ]),
            ]);
        });

        // Event untuk mencatat aktivitas perubahan data absensi
        static::updated(function ($model) {
            $changes = $model->getChanges();
            $original = $model->getOriginal();

            // Cek jika ada perubahan pada kolom 'thr' dan tolak jika ya
            if (array_key_exists('thr', $changes)) {
                return;
            }

            // Hanya simpan atribut yang berubah pada data absensi
            $from = [];
            $to = [];
            foreach ($changes as $key => $value) {
                if (in_array($key, ['absen_masuk', 'absen_keluar'])) {
                    $from[$key] = $original[$key];
                    $to[$key] = $value;
                }
            }

            // Hanya mencatat jika ada perubahan relevan pada data absensi
            if (!empty($from) || !empty($to)) {
                AdminActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'update',
                    'from' => json_encode($from),
                    'to' => json_encode($to),
                ]);
            }
        });

        // Event untuk mencatat aktivitas penghapusan data absensi
        static::deleted(function ($model) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'from' => json_encode([
                    'absen_masuk' => $model->absen_masuk,
                    'absen_keluar' => $model->absen_keluar,
                ]),
                'to' => null,
            ]);
        });

        // Perhitungan THR dilakukan tanpa pengaruh pada log admin activity
        static::saved(function ($absensi) {
            THR::calculateAndSaveTHR($absensi->karyawan_id);
        });

        static::deleted(function ($absensi) {
            THR::calculateAndSaveTHR($absensi->karyawan_id);
        });
    }




    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }


}
