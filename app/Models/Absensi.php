<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';
    protected $primaryKey = 'id_absensi';
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'durasi',
        'status',
        'keterangan'
    ];

    public function getJamMasukAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getJamKeluarAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getDurasiAttribute()
    {
        if ($this->jam_masuk && $this->jam_keluar) {
            $jamMasuk = Carbon::parse($this->jam_masuk);
            $jamKeluar = Carbon::parse($this->jam_keluar);
            $durasi = $jamKeluar->diff($jamMasuk);

            return $durasi->format('%H:%I');
        }

        return '00:00';
    }

    protected static function boot()
    {
        parent::boot();

        // Event untuk mencatat aktivitas pembuatan data absensi
        static::created(function ($model) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'from' => null,
                'to' => json_encode([
                    'jam_masuk' => $model->jam_masuk,
                    'jam_keluar' => $model->jam_keluar,
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
                if (in_array($key, ['jam_masuk', 'jam_keluar'])) {
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
                    'jam_masuk' => $model->jam_masuk,
                    'jam_keluar' => $model->jam_keluar,
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
