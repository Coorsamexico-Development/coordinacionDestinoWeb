<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\BitacoraCampo;
use App\Models\TiposCampo;

class OpcionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoAutocomplete = TiposCampo::where('nombre', 'autocomplete')->first();
        $campoMotivo = BitacoraCampo::where('nombre', 'Motivo de maniobra')->first();


        if ($campoMotivo && $tipoAutocomplete) {
            $campoMotivo->update([
                'tipo_campo_id' => $tipoAutocomplete->id
            ]);

            $opciones = [
                "TARIMAS LADEADAS",
                "REAGENDACION DE CITA",
                "TARIMAS DAÑADAS",
                "APOYO SOLICITADO",
                "LLEGADA TARDIA A CEDIS/APOYO EN DESCARGA",
                "APOYO EN DESCARGA POR MERCANCIA CONTAMINADA TARIMAS POCO PLAYO",
                "APOYO EN DESCARGA (RIESGO DE AUSENCIA 2DO TIRO)",
                "TARIMAS CON DAÑO"
            ];

            foreach ($opciones as $opcion) {
                DB::table('opciones')->updateOrInsert(
                    [
                        'bitacora_campo_id' => $campoMotivo->id,
                        'value' => $opcion
                    ],
                    [
                        'activo' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }
    }
}
