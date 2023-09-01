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
        return $this->hasMany(Incidencia::class, 'incidencia_id');
    }
}
