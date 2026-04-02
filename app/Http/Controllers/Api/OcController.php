<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConfirmacionDt;
use App\Models\Oc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OcController extends Controller
{
    public function index(Request $request, ConfirmacionDt $confirmacionDt)
    {
         return Oc::selectIncidencias($request->producto_id)
            ->where('ocs.confirmacion_dt_id', '=', $confirmacionDt->id)
            ->get();
    }

  

    public function store(ConfirmacionDt $confirmacionDt, Request $request)
    {
        $validated = $request->validate([
            'referencia' => 'required|string|max:255',
            'facturado' => 'required|integer',
        ]);

        $oc = Oc::updateOrCreate([
            'referencia' => $validated['referencia'],
            'confirmacion_dt_id' => $confirmacionDt->id,
        ], [
            'facturado' => $validated['facturado'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OC creada correctamente',
            'oc' => $oc
        ], 201);
    }


    public function saveFacturados(Request $request)
    {
        
        $validated = $request->validate([
            'ocs' => ['array'],
            'ocs.*.id' => ['required', 'integer', 'exists:ocs,id'],
            'ocs.*.facturado' => ['required', 'integer'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['ocs'] as $data) {
                    Oc::where('id', $data['id'])
                        ->update(['facturado' => $data['facturado']]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Registros actualizados correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los registros',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function saveCuadre(Request $request)
    {
        $request->validate([
            'ocs' => ['array'],
            'ocs.*.confirmacion_dt_id' => ['required'],
            'ocs.*.referencia' => ['required', 'string'],
            'ocs.*.enPOD' => ['required', 'integer'],
        ]);
        $ocsCuadradas =  $request['ocs'];
        if (count($ocsCuadradas) !== 0) {
            for ($i = 0; $i < count($ocsCuadradas); $i++) {
                $oc = $ocsCuadradas[$i];
                if ($oc['pod'] !== null) {
                    Oc::where('confirmacion_dt_id', '=', $oc['confirmacion_dt_id'])
                        ->where('referencia', '=', $oc['referencia'])
                        ->update([
                            'enPOD' => $oc['pod']
                        ]);
                } else {
                    Oc::where('confirmacion_dt_id', '=', $oc['confirmacion_dt_id'])
                        ->where('referencia', '=', $oc['referencia'])
                        ->update([
                            'enPOD' => $oc['enPOD']
                        ]);
                }
            }
        }
    }
}
