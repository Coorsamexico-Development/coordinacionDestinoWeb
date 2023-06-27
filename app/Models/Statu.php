<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statu extends Model
{
    use HasFactory;

    public function confirmacionesDts ()
    {
        return $this->hasMany(ConfirmacionDt::class,'status_id');
    }

    public function status_hijos()
    {
        return $this->hasMany(Statu::class, 'status_padre');
    }
}
