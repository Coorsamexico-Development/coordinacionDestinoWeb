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

    /**
     * Almacena múltiples archivos en el almacenamiento en la nube (GCS), crea o actualiza
     * los registros de valor asociados a múltiples campos y una confirmación,
     * y desactiva los valores previos para cada campo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeFile(Request $request)
    {
        $request->validate([
            'confirmacion_id' => 'required|exists:confirmacion_dts,id',
            'campos' => 'required|array',
            'campos.*.id' => 'required|exists:campos,id',
            'campos.*.file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);
        $userId = auth()->id();

        try {
            DB::beginTransaction();
            $responses = [];

            foreach ($request->campos as $index => $campoData) {
                // Obtenemos el archivo usando el índice del array
                $file = $request->file("campos.{$index}.file");
                $campoId = $campoData['id'];

                $path = $file->store('docs/campo-dt-valor/' . $campoId, 'gcs');
                $urlFile = Storage::disk('gcs')->url($path);

                $dtCampoValor = DtCampoValor::firstOrCreate([
                    'confirmacion_id' => $request->confirmacion_id,
                    'campo_id' => $campoId
                ]);

                // Desactivar valores previos para este campo
                Valor::where('dt_campo_valor_id', $dtCampoValor->id)->update(['activo' => 0]);

                // Crear nuevo valor
                Valor::create([
                    'valor' => $urlFile,
                    'dt_campo_valor_id' => $dtCampoValor->id,
                    'user_id' => $userId,
                    'activo' => 1
                ]);

                $responses[] = [
                    'campo_id' => $campoId,
                    'url' => $urlFile
                ];
            }

            DB::commit();

            return response()->json([
                'message' => 'Archivos guardados correctamente',
                'data' => $responses
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'message' => 'Error al guardar los archivos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
