<?php

namespace Database\Seeders;

use App\Models\Campo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCamposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campo = Campo::where('nombre', 'Documento')
            ->where('status_id', 9)
            ->first();
        if ($campo != null) {
            $campo->update([
                'nombre' => "BITACORA",
            ]);
        }
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
            'nombre' => "FACTURA",
            'tipo_campo_id' => 1,
            'status_id' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('campos')->insert([
            'nombre' => "FOLIO INTERNO",
            'tipo_campo_id' => 1,
            'status_id' => 6,
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
