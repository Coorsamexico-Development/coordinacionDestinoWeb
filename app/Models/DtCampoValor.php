<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtCampoValor extends Model
{
    use HasFactory;

    protected $fillable = [
        'confirmacion_id',
        'campo_id'
    ];

    public function valores()
    {
        return $this->hasMany(Valor::class,'dt_campo_valor_id');
    }

}
