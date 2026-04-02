<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class StatusDt extends Model
{
    use HasFactory;

    protected $fillable = [
        'confirmacion_dt_id',
        'status_id',
        'activo',
    ];

    public function status ()
    {
        return $this->hasOne(Statu::class,'id','status_id');
    }

    
     /**
     * Cast para devolver created_at en America/Mexico_City pero guardar en UTC
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
             set: fn ($value) => $value ? Carbon::parse($value)->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : null,
        );
    }

    /**
     * Cast para devolver updated_at en America/Mexico_City pero guardar en UTC
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Carbon::parse($value)->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : null,
        );
    }
}
