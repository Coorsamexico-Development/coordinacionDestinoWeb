<?php

namespace Database\Seeders;

use App\Models\EmailGroup;
use App\Models\EmailGroupRecipient;
use Illuminate\Database\Seeder;

class EmailGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group = EmailGroup::firstOrCreate(
            ['name' => 'customer service'],
            [
                'description' => 'Revisión preliminar customers',
                'active'      => true,
            ]
        );

        $recipients = [
            'cs.purina@coorsamexico.com',
            'op.destino@coorsamexico.com',
            'op.incidencias@coorsamexico.com',
        ];

        foreach ($recipients as $email) {
            EmailGroupRecipient::firstOrCreate(
                [
                    'email_group_id' => $group->id,
                    'email'          => $email,
                ],
                [
                    'name' => null,
                    'type' => 'to',
                ]
            );
        }
    }
}
