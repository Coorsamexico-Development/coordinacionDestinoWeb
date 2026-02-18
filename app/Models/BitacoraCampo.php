<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraCampo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_campo_id',
        'activo'
    ];

    public function tipoCampo()
    {
        return $this->belongsTo(TiposCampo::class, 'tipo_campo_id');
    }

    public function valores()
    {
        return $this->hasMany(BitacoraValor::class, 'bitacora_campo_id');
    }


    function scopeSelectValores($query, $confirmacion_id)
    {

        return $query->select(
            'bitacora_campos.*',
            'tipos_campos.nombre as tipo_campo'
        )
            ->with([
                'valores' => function ($query) use ($confirmacion_id) {
                    $query->where('confirmacion_dt_id', $confirmacion_id)
                        ->where('bitacora_valores.activo', 1);
                }
            ])
            ->join('tipos_campos', 'bitacora_campos.tipo_campo_id', '=', 'tipos_campos.id')
            ->leftJoin('bitacora_valores', function ($join) use ($confirmacion_id) {
                $join->on('bitacora_campos.id', '=', 'bitacora_valores.bitacora_campo_id')
                    ->where('bitacora_valores.confirmacion_dt_id', $confirmacion_id)
                    ->where('bitacora_valores.activo', 1);
            })
            ->where(function ($query) {
                $query->where('bitacora_campos.activo', 1)
                    ->orWhereNull('bitacora_valores.confirmacion_dt_id');
            })
            ->distinct()
            ->get();
    }
}
