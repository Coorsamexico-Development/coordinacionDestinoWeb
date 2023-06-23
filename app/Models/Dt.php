<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dt extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'referencia_dt',
    ];
}
