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

    public static function sendToGroup(string $groupName, \Illuminate\Contracts\Mail\Mailable $mailable)
    {
        $emailGroup = self::where('name', $groupName)->first();

        if ($emailGroup) {
            $recipients = $emailGroup->recipients()->get();
            $emailsTo = $recipients->where('type', 'to')->pluck('email')->toArray();
            $emailsCc = $recipients->where('type', 'cc')->pluck('email')->toArray();
            $emailsBcc = $recipients->where('type', 'bcc')->pluck('email')->toArray();

            \Illuminate\Support\Facades\Mail::to($emailsTo)
                ->cc($emailsCc)
                ->bcc($emailsBcc)
                ->send($mailable);
        }
    }
}
