<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CamposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Campos para pantalla de llegada
        // DB::table('campos')->insert([
        //     'nombre' => "Cajas",
        //     'tipo_campo_id' => 1,
        //     'status_id' => 4,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('campos')->insert([
        //     'nombre' => "Tracto",
        //     'tipo_campo_id' => 1,
        //     'status_id' => 4,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('campos')->insert([
        //     'nombre' => "Remolque",
        //     'tipo_campo_id' => 1,
        //     'status_id' => 4,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('campos')->insert([
        //     'nombre' => "Nombre operador",
        //     'tipo_campo_id' => 1,
        //     'status_id' => 4,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('campos')->insert([
        //     'nombre' => "Teléfono",
        //     'tipo_campo_id' => 2,
        //     'status_id' => 4,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // //Campos para pantalla de documentacion
        // DB::table('campos')->insert([
        //     'nombre' => "YMS",
        //     'tipo_campo_id' => 3,
        //     'status_id' => 6,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('campos')->insert([
        //     'nombre' => "OCYT",
        //     'tipo_campo_id' => 3,
        //     'status_id' => 6,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('campos')->insert([
        //     'nombre' => "Notas",
        //     'tipo_campo_id' => 1,
        //     'status_id' => 6,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        // //Campos para enrrampe/desenrrampe
        // DB::table('campos')->insert([
        //     'nombre' => "Rampa",
        //     'tipo_campo_id' => 1,
        //     'status_id' => 7,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('campos')->insert([
        //     'nombre' => "Puertas",
        //     'tipo_campo_id' => 3,
        //     'status_id' => 7,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('campos')->insert([
        //     'nombre' => "Caja",
        //     'tipo_campo_id' => 3,
        //     'status_id' => 7,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('campos')->insert([
        //     'nombre' => "Sello",
        //     'tipo_campo_id' => 3,
        //     'status_id' => 7,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // //Campos para desenrampe
        // DB::table('campos')->insert([
        //     'nombre' => "Comentarios",
        //     'tipo_campo_id' => 1,
        //     'status_id' => 9,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('campos')->insert([
        //     'nombre' => "BITACORA",
        //     'tipo_campo_id' => 4,
        //     'status_id' => 9,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);


        // Pantalla en riesgo
        DB::table('campos')->insert([
            'nombre' => "Cajas",
            'tipo_campo_id' => 1,
            'status_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('campos')->insert([
            'nombre' => "Tracto",
            'tipo_campo_id' => 1,
            'status_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('campos')->insert([
            'nombre' => "Remolque",
            'tipo_campo_id' => 1,
            'status_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('campos')->insert([
            'nombre' => "Nombre operador",
            'tipo_campo_id' => 1,
            'status_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('campos')->insert([
            'nombre' => "Teléfono",
            'tipo_campo_id' => 2,
            'status_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        DB::table('campos')->insert([
            'nombre' => "POD",
            'tipo_campo_id' => 4,
            'status_id' => 9,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
