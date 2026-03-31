<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oc extends Model
{
    use HasFactory;

    protected $fillable = [
        'referencia',
        'confirmacion_dt_id',
        'facturado',
        'en POD',
        'bandera'
    ];

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class,'ocs_id');
    }

    public function scopeSelectIncidencias($query, ?int $productoId = null)
    {
        return $query->select('ocs.*')
                ->with([
                'incidencias'  => function ($query) use ($productoId) {
                    $query->select(
                        'incidencias.*',
                        'tipo_incidencias.nombre as tipo_incidencia',
                        'productos.clave_producto'
                    )
                        ->with('evidencias')
                        ->join('tipo_incidencias', 'incidencias.tipo_incidencia_id', 'tipo_incidencias.id')
                        ->join('productos', 'incidencias.producto_id', 'productos.id');

                    if ($productoId !== null) {
                        $query->where('incidencias.producto_id', '=', $productoId);
                    }
                    $query->get();
                }
            ]);
    }
}
