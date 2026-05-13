<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oc extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'referencia',
        'confirmacion_dt_id',
        'facturado',
        'en POD',
        'bandera'
    ];

    public function facturas()
    {
        return $this->belongsToMany(Factura::class, 'factura_oc', 'oc_id', 'factura_id');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class,'ocs_id');
    }

    public function scopeSelectIncidencias($query, ?int $productoId = null)
    {
        return $query->select('ocs.*')
                ->with([
                'incidencias'  => function ($query) use ($productoId) {
                    $query->withDetails();

                    if ($productoId !== null) {
                        $query->where('incidencias.producto_id', '=', $productoId);
                    }
                }
            ]);
    }
}
