<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraValor extends Model
{
    use HasFactory;

    protected $table = 'bitacora_valores';

    protected $fillable = [
        'confirmacion_dt_id',
        'bitacora_campo_id',
        'valor',
        'activo',
        'user_id'
    ];

    public function campo()
    {
        return $this->belongsTo(BitacoraCampo::class, 'bitacora_campo_id');
    }

    public function confirmacion()
    {
        return $this->belongsTo(ConfirmacionDt::class, 'confirmacion_dt_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
