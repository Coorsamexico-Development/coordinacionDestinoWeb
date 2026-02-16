<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailGroupRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_group_id',
        'email',
        'name',
        'type',
    ];

    public function group()
    {
        return $this->belongsTo(EmailGroup::class, 'email_group_id');
    }
}
