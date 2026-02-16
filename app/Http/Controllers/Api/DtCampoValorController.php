<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campo;
use App\Models\DtCampoValor;
use App\Models\Valor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DtCampoValorController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function storeEvidencias(Campo $campo, Request $request)
    {

        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png',
            'confirmacion_id' => 'required|exists:confirmacion_dts,id',

        ]);
        $userId = auth()->id();
        try {
            DB::beginTransaction();
            $dtCampoValor = DtCampoValor::firstOrCreate([
                'confirmacion_id' => $request->confirmacion_id,
                'campo_id' => $campo->id
            ]);
            foreach ($request->file('files') as $file) {
                $ruta_file =
                    $file->store('docs/campo-dt-valor/' . $dtCampoValor->id, 'gcs');
                $urlFile = Storage::disk('gcs')->url($ruta_file);

                Valor::create([
                    'valor' => $urlFile,
                    'dt_campo_valor_id' => $dtCampoValor->id,
                    'user_id' => $userId,
                    'is_evidencia' => true,
                ]);
            }
            DB::commit();
            return response()->json([
                'message' => 'Archivo guardado correctamente',
                'data' => $dtCampoValor
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'message' => 'Error al guardar el archivo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
