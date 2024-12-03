<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AbsenController;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absen extends Model
{
    use HasFactory;

    protected $table = 'absen'; // Nama tabel
    protected $fillable = ['karyawan_id', 'jam_masuk', 'jam_keluar', 'hadir', 'sakit', 'izin', 'alpha', 'keterangan'];

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
        // static::saved(function ($absen) {
        //     Thr::calculateAndSaveTHR($absen->karyawan_id);
        // });

        // static::deleted(function ($absen) {
        //     Thr::calculateAndSaveTHR($absen->karyawan_id);
        // });


        static::created(function ($model) {
            self::logAdminActivity('create', null, $model->getAttributes());

            // Hitung alpha menggunakan controller
            $controller = new \App\Http\Controllers\AbsenController();
            $controller->hitungHadir($model);
            $controller->hitungAlpha($model);
        });

        static::updated(function ($model) {
            $original = $model->getOriginal();
            $changes = $model->getDirty();

            // Hanya log jika ada perubahan selain kolom `timestamps`
            $relevantChanges = array_filter($changes, function ($key) {
                return !in_array($key, ['updated_at']);
            });

            if (!empty($relevantChanges)) {
                $from = array_intersect_key($original, $relevantChanges);
                $to = array_intersect_key($model->getAttributes(), $relevantChanges);

                self::logAdminActivity('update', $from, $to);
            }
        });

        static::deleted(function ($model) {
            self::logAdminActivity('delete', $model->getAttributes(), null);
        });

    }

    protected static function logAdminActivity($action, $from, $to)
    {
        AdminActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'from' => $from ? json_encode($from) : null,
            'to' => $to ? json_encode($to) : null,
        ]);
    }

    // public function absen_masuk()
    // {
    //     return $this->belongsTo(AbsenMasuk::class, 'absen_masuk_id', 'id_absen_masuk');
    // }

    // // Relasi ke tabel absen_keluar
    // public function absen_keluar()
    // {
    //     return $this->belongsTo(AbsenKeluar::class, 'absen_keluar_id', 'id_absen_keluar');
    // }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }
}
