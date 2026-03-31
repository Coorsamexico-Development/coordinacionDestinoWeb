<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evidencia;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IncidenciaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {

        $request->validate([
            'oc_id' => 'required|integer',
            'tipo_incidencia_id' => 'required|integer',
            'cantidad' => 'required|numeric',
            'producto_id' => 'required|exists:productos,id', // This corresponds to producto_id/product_id
            'upc_or_sku' => 'required|string',
            'evidencias' => 'nullable|array',
            'evidencias.*' => 'file|image|max:10240', // Max 10MB per image
        ]);


        $incidencia = Incidencia::updateOrCreate(
            [
                'ocs_id' => $request->oc_id,
                'tipo_incidencia_id' => $request->tipo_incidencia_id,
                'producto_id' => $request->producto_id // producto_id corresponds to product id
            ],
            [
                'cantidad' => $request->cantidad,
                'upc_or_sku' => $request->upc_or_sku
            ]
        );

        // Handle Evidencias
        if ($request->hasFile('evidencias')) {
            $evidencias = $request->file('evidencias');

            foreach ($evidencias as $file) {
                $nombre = $file->getClientOriginalName();
                // Using 'gcs' disk as per existing controller
                $rutaImage = $file->storeAs('img/fotos', time() . '_' . $nombre, 'gcs');
                $urlImage = Storage::disk('gcs')->url($rutaImage);

                Evidencia::create([
                    'evidencia' => $urlImage,
                    'incidencia_id' => $incidencia->id
                ]);
            }
        }

        return response()->json([
            'message' => 'Incidencia guardada correctamente',
            'data' => $incidencia->load('evidencias')
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $incidencia = Incidencia::findOrFail($id);

            // Delete associated evidences
            // Note: If actual file deletion from storage is required, iterating would be needed.
            // Following existing pattern of DB deletion for now.
            $incidencia->evidencias()->delete();

            $incidencia->delete();

            return response()->json(['message' => 'Incidencia eliminada correctamente'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Incidencia no encontrada'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting incidencia: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar la incidencia'], 500);
        }
    }
}
