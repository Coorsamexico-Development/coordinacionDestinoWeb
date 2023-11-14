<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class confirmacionStatusPod extends Model
{
    use HasFactory;
    protected $fillable = [
        'confirmacion_dt_id',
        'status_pod_id',
        'activo',
    ];
}
