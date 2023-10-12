<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmacionDt extends Model
{
    use HasFactory;

    protected $fillable = 
    ['confirmacion', 
    'dt_id',
    'cita',
    'numero_cajas',
    'linea_transporte_id',
    'plataforma_id',
    'cliente_id',
    'ubicacion_id',
    'status_id'
     ];

     public function ocs()
     {
         return $this->hasMany(Oc::class,'confirmacion_dt_id');
     }
}
