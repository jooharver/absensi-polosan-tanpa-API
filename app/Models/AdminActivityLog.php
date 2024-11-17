<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $table = 'admin_activity_logs';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'user_id',
        'action',
        'from',
        'to',
        'action_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
