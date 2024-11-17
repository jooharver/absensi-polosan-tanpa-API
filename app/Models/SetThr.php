<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetThr extends Model
{
    use HasFactory;

    protected $table = 'set_thrs';
    protected $primaryKey = 'id_set_thr';

    protected $fillable = ['posisi_id', 'besaran_thr'];

    // Boot method to register model events
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

            // Variabel untuk menyimpan data 'from' dan 'to' dengan kolom id_thr dan nama posisi
            $from = [
                'id_set_thr' => $original['id_set_thr'],

            ];
            $to = [
                'id_set_thr' => $model['id_set_thr'],

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

              // Event ketika set_thr diupdate
              static::updated(function ($setThr) {
                // Panggil fungsi untuk memperbarui THR untuk semua karyawan dengan posisi_id yang sama
                THR::updateTHRForPosisi($setThr->posisi_id);
            });

            // Event ketika set_thr dihapus
            static::deleted(function ($setThr) {
                // Panggil fungsi untuk menghapus THR untuk semua karyawan dengan posisi_id yang sama
                THR::removeTHRForPosisi($setThr->posisi_id);
            });
    }

    public function thrs()
    {
        return $this->hasMany(Thr::class, 'tahun', 'tahun');
    }

    // In SetThr.php
    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'posisi_id', 'id_posisi');
    }

}

