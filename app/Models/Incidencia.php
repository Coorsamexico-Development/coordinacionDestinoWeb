<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'ocs_id',
        'tipo_incidencia_id',
        'cantidad',
        'producto_id',
        'upc_or_sku',
        'cantidadPOD',
        'factura_id'
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'incidencia_id');
    }

    public function tipoIncidencia()
    {
        return $this->belongsTo(TipoIncidencia::class, 'tipo_incidencia_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function scopeWithDetails($query)
    {
        return $query->select(
            'incidencias.*',
            'tipo_incidencias.nombre as tipo_incidencia',
            'productos.descripcion as producto',
            'facturas.factura',
            'productos.clave_producto',
            'productos.SKU as sku'
        )
        ->with('evidencias')
        ->leftJoin('facturas', 'incidencias.factura_id', 'facturas.id')
        ->join('tipo_incidencias', 'incidencias.tipo_incidencia_id', 'tipo_incidencias.id')
        ->join('productos', 'incidencias.producto_id', 'productos.id')
        ->orderBy('incidencias.id', 'ASC');
    }
}
