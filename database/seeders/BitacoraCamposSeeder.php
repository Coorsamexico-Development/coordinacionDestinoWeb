<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BitacoraCamposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campos = [
            ['nombre' => 'Motivo de maniobra', 'tipo_campo_id' => 1], // Text
            ['nombre' => 'Nombre del personal de Walmart que solicitó la maniobra', 'tipo_campo_id' => 1], // Text
            ['nombre' => 'Coordinador COORSA que solicita maniobra', 'tipo_campo_id' => 1], // Text
            ['nombre' => 'NÚMERO DE TARIMAS/CAJAS', 'tipo_campo_id' => 1], // Text
            ['nombre' => 'Costo por tarima/Costo por caja', 'tipo_campo_id' => 2], // Number
            ['nombre' => 'Costo total', 'tipo_campo_id' => 2], // Number
            ['nombre' => 'Maniobra autorizada por', 'tipo_campo_id' => 1], // Text
            ['nombre' => 'Autorización Transporte', 'tipo_campo_id' => 1], // Text
            ['nombre' => 'INFORMACIÓN DEL PAGO (PISO/ CUENTA BANCARIA)', 'tipo_campo_id' => 1], // Text
            ['nombre' => 'EVIDENCIAS', 'tipo_campo_id' => 4], // File
        ];

        foreach ($campos as $campo) {
            DB::table('bitacora_campos')->updateOrInsert(
                ['nombre' => $campo['nombre']],
                [
                    'tipo_campo_id' => $campo['tipo_campo_id'],
                    'activo' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
