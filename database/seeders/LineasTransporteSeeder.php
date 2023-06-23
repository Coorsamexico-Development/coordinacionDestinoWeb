<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LineasTransporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('linea_transportes')->insert([
            'nombre' => "MONROY",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('linea_transportes')->insert([
            'nombre' => "CASTORES",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('linea_transportes')->insert([
            'nombre' => "CEDIS",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
