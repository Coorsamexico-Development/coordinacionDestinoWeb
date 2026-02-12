<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valor extends Model
{
    use HasFactory;

    protected $fillable = [
        'valor',
        'dt_campo_valor_id',
        'user_id',
        'activo',
        'is_evidencia'
    ];

    protected $casts = [
        'is_evidencia' => 'boolean',
        'activo' => 'boolean',
    ];
}
