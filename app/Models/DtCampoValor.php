<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtCampoValor extends Model
{
    use HasFactory;

    protected $fillable = [
        'dt_id',
        'campo_id'
    ];
}
