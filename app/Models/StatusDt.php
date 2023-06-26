<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusDt extends Model
{
    use HasFactory;

    protected $fillable = [
        'confirmacion_dt_id',
        'status_id',
        'activo',
    ];
}
