<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plataforma extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'clave',
        'activo',
    ];

    public function confirmacionesDts ()
    {
        return $this->hasMany(ConfirmacionDt::class,'plataforma_id');
    }

}
