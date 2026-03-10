<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    use HasFactory;

    protected $table = 'opciones';

    protected $fillable = [
        'bitacora_campo_id',
        'value',
        'activo'
    ];

    public function bitacoraCampo()
    {
        return $this->belongsTo(BitacoraCampo::class, 'bitacora_campo_id');
    }
}
