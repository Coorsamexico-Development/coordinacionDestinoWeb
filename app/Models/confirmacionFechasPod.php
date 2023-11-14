<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class confirmacionFechasPod extends Model
{
    use HasFactory;
    protected $fillable = [
        'confirmacion_dt_id',
        'fecha_pod_id',
        'activo'
    ];
}
