<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BitacoraCampo;
use App\Models\BitacoraValor;
use App\Models\ConfirmacionDt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\BitacoraReportMail;
use App\Models\EmailGroup;
use Illuminate\Support\Facades\Log;

class BitacoraController extends Controller
{
    /**
     * Get all active Bitacora fields.
     */
    public function indexByConfirmacion(ConfirmacionDt $confirmacionDt)
    {
        return
            $campos = BitacoraCampo::selectValores($confirmacionDt->id);

        return response()->json($campos);
    }

    /**
     * Store a new Bitacora field.
     */
    public function storeCampo(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_campo_id' => 'required|exists:tipos_campos,id',
        ]);

        $campo = BitacoraCampo::create([
            'nombre' => $request->nombre,
            'tipo_campo_id' => $request->tipo_campo_id,
            'activo' => true,
        ]);

        return response()->json($campo, 201);
    }

    /**
     * Update/Deactivate a Bitacora field.
     */
    public function updateCampo(Request $request, $id)
    {
        $campo = BitacoraCampo::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'activo' => 'sometimes|boolean',
        ]);

        $campo->update($request->only(['nombre', 'activo']));

        return response()->json($campo);
    }



    /**
     * Store values for Bitacora fields.
     */
    public function storeValores(Request $request)
    {
        $request->validate([
            'confirmacion_id' => 'required|numeric',
            'valores' => 'array',
            'valores.*.campo_id' => 'required|exists:bitacora_campos,id',
            'valores.*.valor' => 'nullable',
        ]);

        $userId = auth()->id();
        $confirmacionId = $request->confirmacion_id;

        $confirmacion = ConfirmacionDt::findOrFail($confirmacionId);

        DB::beginTransaction();
        try {
            // Handle text/number values
            if ($request->has('valores')) {
                foreach ($request->valores as $item) {
                    $campoId = $item['campo_id'];
                    $valorValue = $item['valor'] ?? null;

                    if ($valorValue === null) continue;

                    BitacoraValor::updateOrCreate(
                        ['confirmacion_dt_id' => $confirmacionId, 'bitacora_campo_id' => $campoId],
                        [
                            'valor' => $valorValue,
                            'user_id' => $userId,
                        ]
                    );
                }
            }

            DB::commit();

            EmailGroup::sendToGroup('customer service', new BitacoraReportMail($confirmacion));



            return response()->json(['message' => 'Bitacora saved successfully']);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store files for a specific Bitacora field.
     */
    public function storeFiles(Request $request)
    {
        $request->validate([
            'confirmacion_id' => 'required|exists:confirmacion_dts,id',
            'bitacora_campo_id' => 'required|exists:bitacora_campos,id',
            'files' => 'required|array',
            'files.*' => 'file',
        ]);

        $confirmacionId = $request->confirmacion_id;
        $campoId = $request->bitacora_campo_id;
        $userId = auth()->id();

        DB::beginTransaction();
        try {
            $uploadedUrls = [];

            foreach ($request->file('files') as $file) {
                // Store file in GCS
                $ruta_file = $file->store('docs/bitacora/' . $confirmacionId, 'gcs');
                $urlFile = Storage::disk('gcs')->url($ruta_file);

                // Save new record (allow multiple files per field)
                BitacoraValor::create([
                    'confirmacion_dt_id' => $confirmacionId,
                    'bitacora_campo_id' => $campoId,
                    'valor' => $urlFile,
                    'user_id' => $userId,
                ]);

                $uploadedUrls[] = $urlFile;
            }

            DB::commit();
            return response()->json(['message' => 'Files saved successfully', 'urls' => $uploadedUrls]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
