<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Oc;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    /**
     * Consultar facturas.
     */
    public function index(Request $request)
    {
        $query = Factura::query();

        $query->with([
            'ocs',
            'incidencias' => function ($q) {
                $q->withDetails();
            }
        ]);

        if ($request->has('confirmacion_dt_id')) {
            $query->whereHas('ocs', function ($q) use ($request) {
                $q->where('confirmacion_dt_id', $request->confirmacion_dt_id);
            });
        }

        if ($request->has('confirmacion')) {
            $query->whereHas('ocs.confirmacionDt', function ($q) use ($request) {
                $q->where('confirmacion', $request->confirmacion);
            });
        }

        return $query->get();
    }

    /**
     * Crear o actualizar una factura.
     */
    public function store(Request $request)
    {
        $request->validate([
            'oc_referencias' => 'required|array',
            'oc_referencias.*' => 'exists:ocs,referencia',
            'factura' => 'required|string',
        ]);

        $factura = Factura::firstOrCreate(
            ['factura' => $request->factura]
        );

        $ocIds = Oc::whereIn('referencia', $request->oc_referencias)->pluck('id');

        // Asociar las OCs a la factura
        $factura->ocs()->syncWithoutDetaching($ocIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Factura procesada correctamente',
            'data' => $factura
        ]);
    }

    /**
     * Eliminar una factura.
     */
    public function destroy(Factura $factura)
    {
        if ($factura->incidencias()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se puede eliminar la factura porque tiene incidencias relacionadas'
            ], 422);
        }

        $factura->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Factura eliminada correctamente'
        ]);
    }

    /**
     * Desasociar una factura de una OC.
     */
    public function detachOc(Request $request)
    {
        $request->validate([
            'factura' => 'required|exists:facturas,factura',
            'oc_referencia' => 'required|exists:ocs,referencia',
        ]);

        $factura = Factura::where('factura', $request->factura)->firstOrFail();
        $oc = Oc::where('referencia', $request->oc_referencia)->firstOrFail();
        
        $factura->ocs()->detach($oc->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Factura desasociada de la OC correctamente'
        ]);
    }
}
