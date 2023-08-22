<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorasHistorico extends Model
{
    use HasFactory;

    protected $fillable = 
    ['hora_id', 
     'status_dts_id',
     'hora'
     ];
}
