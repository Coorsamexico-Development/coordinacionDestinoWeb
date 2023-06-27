<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacione extends Model
{
    use HasFactory;
    
    protected $fillable = ['nombre_ubicacion', 'abreviacion'];

    public function confirmacionesDts ()
    {
        return $this->hasMany(ConfirmacionDt::class,'ubicacion_id');
    }
}
