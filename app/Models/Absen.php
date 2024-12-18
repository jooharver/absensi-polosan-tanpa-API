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

        // Perhitungan THR dilakukan tanpa pengaruh pada log admin activity
        static::saved(function ($absen) {
            Thr::calculateAndSaveTHR($absen->karyawan_id);
        });

        static::deleted(function ($absen) {
            Thr::calculateAndSaveTHR($absen->karyawan_id);
        });


        static::created(function ($model) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'from' => null, // Karena ini adalah penambahan
                'to' => json_encode($model->getAttributes()),
            ]);

        });

        static::updated(function ($model) {
            $changes = $model->getDirty(); // Mendapatkan data yang berubah
            $original = $model->getOriginal(); // Mendapatkan nilai asli

            // Variabel untuk menyimpan data 'from' dan 'to' dengan tambahan kolom id_karyawan dan nama
            $from = [
                'id' => $original['id'],
                'id_karyawan' => $original['karyawan_id'], // Asumsi nama kolom id_karyawan adalah 'karyawan_id'
                'nama' => Karyawan::find($original['karyawan_id'])->nama, // Ambil nama karyawan berdasarkan id_karyawan
            ];

            $to = [
                'id' => $model->id,
                'id_karyawan' => $model->karyawan_id,
                'nama' => Karyawan::find($model->karyawan_id)->nama, // Ambil nama karyawan berdasarkan id_karyawan
            ];

            // Loop untuk memeriksa perubahan di kolom lain selain yang diabaikan (misalnya tanggal)
            foreach ($changes as $key => $value) {
                // Mengabaikan kolom yang tidak perlu dicatat, misalnya kolom tanggal dan timestamp
                if ($key !== 'tanggal_masuk' && !in_array($key, ['created_at', 'updated_at'])) {
                    $from[$key] = $original[$key];
                    $to[$key] = $value;
                }
            }

            // Pastikan hanya mencatat jika ada perubahan yang relevan
            if (!empty(array_diff_assoc($from, $to))) {
                AdminActivityLog::create([
                    'user_id' => auth()->id(), // ID pengguna yang melakukan perubahan
                    'action' => 'update', // Jenis aksi, dalam hal ini adalah update
                    'from' => json_encode($from), // Data sebelum perubahan
                    'to' => json_encode($to), // Data setelah perubahan
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

    protected static function logAdminActivity($action, $from, $to)
    {
        AdminActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'from' => $from ? json_encode($from) : null,
            'to' => $to ? json_encode($to) : null,
        ]);
    }


    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }
}
