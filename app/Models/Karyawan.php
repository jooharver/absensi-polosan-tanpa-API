<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';
    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'tanggal_lahir',
        'agama',
        'jenis_kelamin',
        'no_telepon',
        'email',
        'tanggal_masuk',
        'face_vector',
        'posisi_id',
    ];

    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'posisi_id', 'id_posisi');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'karyawan_id', 'id_karyawan');
    }

    public function absen()
    {
        return $this->hasMany(Absen::class, 'karyawan_id', 'id_karyawan');
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

            // Call saveFaceVector after creation
            $imagePath = $model->face_vector; // Replace with the actual attribute name for the image path
            $faceRecognitionController = new \App\Http\Controllers\FaceRecognitionController();
            $faceRecognitionController->saveFaceVector($imagePath, $model->id_karyawan);
        });

        static::updated(function ($model) {
            $changes = $model->getDirty(); // Menggunakan getDirty() untuk mengambil atribut yang diubah sebelum penyimpanan
            $original = $model->getOriginal(); // Mendapatkan nilai asli

            // Variabel untuk menyimpan data 'from' dan 'to' dengan tambahan kolom id_karyawan dan nama
            $from = [
                'id_karyawan' => $original['id_karyawan'],
                'nama' => $original['nama'],
            ];
            $to = [
                'id_karyawan' => $model->id_karyawan,
                'nama' => $model->nama,
            ];

            foreach ($changes as $key => $value) {
                // Mengabaikan kolom 'tanggal_masuk' dan kolom timestamp
                if ($key !== 'tanggal_masuk' && !in_array($key, ['created_at', 'updated_at'])) {
                    // Menyimpan atribut yang diubah
                    $from[$key] = $original[$key];
                    $to[$key] = $value;
                }
            }

            if (!empty(array_diff_assoc($from, $to))) { // Pastikan hanya mencatat jika ada perubahan yang relevan
                AdminActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'update',
                    'from' => json_encode($from), // Hanya atribut yang diubah
                    'to' => json_encode($to), // Hanya atribut yang diubah
                ]);
            }

            // // Call saveFaceVector after update
            $imagePath = $model->face_vector; // Replace with the actual attribute name for the image path
            $faceRecognitionController = new \App\Http\Controllers\FaceRecognitionController();
            $faceRecognitionController->saveFaceVector($imagePath, $model->id_karyawan);
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

}
