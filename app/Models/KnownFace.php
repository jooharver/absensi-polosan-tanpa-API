<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnownFace extends Model
{
    protected $fillable = ['name', 'encoding'];

    protected $casts = [
        'encoding' => 'array', // Konversi encoding otomatis ke array saat diambil
    ];
}

