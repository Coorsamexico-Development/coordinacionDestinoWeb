<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class ConfirmacionDt extends Model
{
    use HasFactory, SoftDeletes;

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
        'origen_id',
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

    public function origen()
    {
        return $this->belongsTo(Ubicacione::class, 'origen_id');
    }

    public function dt()
    {
        return $this->belongsTo(Dt::class, 'dt_id');
    }

    public function plataforma()
    {
        return $this->belongsTo(Plataforma::class, 'plataforma_id');
    }

    /**
     * Cast para devolver created_at en America/Mexico_City pero guardar en UTC
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
             set: fn ($value) => $value ? Carbon::parse($value)->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : null,
        );
    }

    /**
     * Cast para devolver updated_at en America/Mexico_City pero guardar en UTC
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Carbon::parse($value)->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : null,
        );
    }
}
