<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUbicacione extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ubicacion_id',
    ];

}
