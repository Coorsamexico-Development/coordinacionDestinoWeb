<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoIncidenciasProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tipo_incidencias')->insert([
            'nombre' => "Faltante",
            'activo' => 1,
            'positivo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tipo_incidencias')->insert([
            'nombre' => "Daño",
            'activo' => 1,
            'positivo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tipo_incidencias')->insert([
            'nombre' => "Cambio de clave",
            'activo' => 1,
            'positivo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tipo_incidencias')->insert([
            'nombre' => "Sobrante",
            'activo' => 1,
            'positivo' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
