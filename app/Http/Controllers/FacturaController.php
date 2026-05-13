<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    /**
     * Consultar facturas.
     */
    public function index(Request $request)
    {
        $query = Factura::query()
         ->with([
            'incidencias' => function ($q) {
                $q->withDetails();
            }
         ]);

        if ($request->has('confirmacion_dt_id')) {
            $query->whereHas('ocs', function ($q) use ($request) {
                $q->where('confirmacion_dt_id', $request->confirmacion_dt_id);
            });
        }

        if ($request->has('oc_id')) {
            $query->whereHas('ocs', function ($q) use ($request) {
                $q->where('ocs.id', $request->oc_id);
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
            'oc_ids' => 'required|array',
            'oc_ids.*' => 'exists:ocs,id',
            'factura' => 'required|string',
        ]);

        $factura = Factura::firstOrCreate(
            ['factura' => $request->factura]
        );

        $factura->ocs()->syncWithoutDetaching($request->oc_ids);

        return response()->json([
            'status' => 'success',
            'message' => 'Factura procesada correctamente',
            'data' => $factura
        ]);
    }

    
}
