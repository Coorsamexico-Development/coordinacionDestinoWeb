<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmacionDt extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'confirmacion',
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
        return $this->hasMany(Oc::class, 'confirmacion_dt_id');
    }

    public function confirmacion_status_pods()
    {
        return $this->hasMany(confirmacionStatusPod::class, 'confirmacion_dt_id');
    }

    public function lineaTransporte()
    {
        return $this->belongsTo(LineaTransporte::class, 'linea_transporte_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacione::class, 'ubicacion_id');
    }
}
