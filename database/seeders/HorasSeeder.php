<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('horas')->insert([
            'nombre' => "Hora de llegada",
            'status_id' => 1,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('horas')->insert([
            'nombre' => "Hora de documentación",
            'status_id' => 2,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('horas')->insert([
            'nombre' => "Hora de descarga",
            'status_id' => 2,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('horas')->insert([
            'nombre' => "Hora de recolección de folios",
            'status_id' => 2,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('horas')->insert([
            'nombre' => "Hora de enrrampe",
            'status_id' => 8,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('horas')->insert([
            'nombre' => "Hora de impresion",
            'status_id' => 3,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
