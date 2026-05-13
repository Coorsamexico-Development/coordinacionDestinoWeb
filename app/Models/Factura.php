<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'factura',
    ];

    public function ocs()
    {
        return $this->belongsToMany(Oc::class, 'factura_oc', 'factura_id', 'oc_id');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'factura_id');
    }
}
