<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlataformaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('plataformas')->insert([
            'nombre' => "WALMART",
            'clave' => 'WALMART',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('plataformas')->insert([
            'nombre' => "SAM´S ",
            'clave' => 'SAMS',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('plataformas')->insert([
            'nombre' => "BAE",
            'clave' => 'BAE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
