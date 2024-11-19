<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'offices';
    protected $primaryKey = 'id_office';
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius',
    ];

}
