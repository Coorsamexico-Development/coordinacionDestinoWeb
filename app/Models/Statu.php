<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statu extends Model
{
    use HasFactory;


    protected $guarded = ['id'];

    public function confirmacionesDts()
    {
        return $this->hasMany(ConfirmacionDt::class, 'status_id');
    }

    public function status_hijos()
    {
        return $this->hasMany(Statu::class, 'status_padre');
    }

    public function campos()
    {
        return $this->hasMany(Campo::class, 'status_id', 'status_padre');
    }

    public function campos2()
    {
        return $this->hasMany(Campo::class, 'status_id');
    }

    public function status_dts()
    {
        return $this->hasMany(StatusDt::class, 'status_id');
    }
}
