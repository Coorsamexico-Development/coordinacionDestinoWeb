<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('productos')->insert([
            'SKU' => "5814",
            'descripcion' => 'AXE DEO AER ENYGMATA 12X113GR/160ML',
            'DUN 14' => '37791293022117',
            'EAN' => '7791293022116',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('productos')->insert([
            'SKU' => "5839",
            'descripcion' => 'REXONA DEO ROLLON AP OXYGEN 12X53G/50ML',
            'DUN 14' => '17891037142920',
            'EAN' => '78914292',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
