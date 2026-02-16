<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    public function recipients()
    {
        return $this->hasMany(EmailGroupRecipient::class);
    }

    public function status()
    {
        return $this->hasMany(Statu::class);
    }
}
