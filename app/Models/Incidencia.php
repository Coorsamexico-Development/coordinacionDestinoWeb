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
        'ean_id',
        'cantidadPOD'
    ];

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class,'incidencia_id');
    }
}
