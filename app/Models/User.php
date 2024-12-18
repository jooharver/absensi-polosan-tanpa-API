<?php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'karyawan_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Menambahkan logika untuk memeriksa role pengguna
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Menambahkan logika untuk memeriksa apakah pengguna adalah super admin
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Event lifecycle: logging activity (created, updated, deleted)
     */
    protected static function boot()
    {
        parent::boot();

        //can't delete super admin
        static::deleting(function ($user) {
            if (in_array($user->id, [1, 2, 3])) {
                // Abort deletion
                throw new \Exception("User with ID {$user->id} cannot be deleted.");
            }
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
            $changes = $model->getChanges();
            $original = $model->getOriginal();

            $from = ['id'];
            $to = [];

            foreach ($changes as $key => $value) {
                if ($key !== 'tanggal_masuk' && !in_array($key, ['created_at', 'updated_at'])) {
                    $from[$key] = $original[$key];
                    $to[$key] = $value;
                }
            }

            if (!empty($from) || !empty($to)) {
                AdminActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'update',
                    'from' => json_encode($from),
                    'to' => json_encode($to),
                ]);
            }
        });

        static::deleted(function ($model) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'from' => json_encode($model->getAttributes()),
                'to' => null,
            ]);
        });
    }

    public function karyawans()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }

    // public function canAccessPanel(Panel $panel): bool
    // {
    //     return $this->hasRole(['Admin', 'Super Admin',]);
    // }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

}
