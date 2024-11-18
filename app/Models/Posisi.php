<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posisi extends Model
{
    use HasFactory;

    protected $table = 'posisis';
    protected $primaryKey = 'id_posisi';

    protected $fillable = [
        'posisi',
        'jam_kerja_per_hari',
        'hari_kerja_per_minggu',
        'batas_masuk',
        'batas_keluar'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($posisi) {
            // When the position is updated, recalculate THR for all associated employees
            THR::updateTHRForPosisi($posisi->id_posisi);
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
            $changes = $model->getChanges(); // Mendapatkan atribut yang diubah
            $original = $model->getOriginal(); // Mendapatkan nilai asli

            // Variabel untuk menyimpan data 'from' dan 'to' dengan tambahan kolom id_karyawan dan nama
            $from = [
                'id_posisi' => $original['id_posisi'],
                'posisi' => $original['posisi'],
            ];
            $to = [
                'id_posisi' => $model['id_posisi'],
                'posisi' => $model['posisi'],
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

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }

    public static function updateTHRForPosisi($posisiId)
    {
        // Ambil semua karyawan dengan posisi_id yang diberikan
        $karyawans = Karyawan::where('posisi_id', $posisiId)->get();

        // Loop melalui setiap karyawan untuk menghitung dan menyimpan THR mereka
        foreach ($karyawans as $karyawan) {
            self::calculateAndSaveTHR($karyawan->id_karyawan);
        }
    }

}
