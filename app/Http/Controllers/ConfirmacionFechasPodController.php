<?php

namespace App\Http\Controllers;

use App\Models\confirmacionFechasPod;
use App\Models\fechasPod;
use Illuminate\Http\Request;

class ConfirmacionFechasPodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return $request;
    }

    public function saveFechasPODConfirmacion(Request $request)
    {
        //return $request;
        //
        switch ($request['tipo']) 
        {
            case 'fechaEnvio':
                //desactivamos los demas registros con misma confirmacion
                   confirmacionFechasPod::where('confirmacion_dt_id','=',$request['confirmacion']) 
                   ->where('fecha_pod_id','=',1)
                   ->update([
                      'activo' => 0
                   ]);

                   confirmacionFechasPod::create([
                      'confirmacion_dt_id' => $request['confirmacion'],
                      'fecha_pod_id' => 1,
                      'fecha' => $request['fecha']
                   ]);

                break;
            case 'fechaLiberacion':
                   //desactivamos los demas registros con misma confirmacion
                   confirmacionFechasPod::where('confirmacion_dt_id','=',$request['confirmacion']) 
                   ->where('fecha_pod_id','=',2)
                   ->update([
                      'activo' => 0
                   ]);

                   confirmacionFechasPod::create([
                      'confirmacion_dt_id' => $request['confirmacion'],
                      'fecha_pod_id' => 2,
                      'fecha' => $request['fecha']
                   ]);
                break;
            case 'fechaRecepcion':
                //desactivamos los demas registros con misma confirmacion
                confirmacionFechasPod::where('confirmacion_dt_id','=',$request['confirmacion']) 
                ->where('fecha_pod_id','=',3)
                ->update([
                   'activo' => 0
                ]);

                confirmacionFechasPod::create([
                   'confirmacion_dt_id' => $request['confirmacion'],
                   'fecha_pod_id' => 3,
                   'fecha' => $request['fecha']
                ]);
                break;
            case 'fechaFacturacion':
                 //desactivamos los demas registros con misma confirmacion
                 confirmacionFechasPod::where('confirmacion_dt_id','=',$request['confirmacion']) 
                 ->where('fecha_pod_id','=',4)
                 ->update([
                    'activo' => 0
                 ]);
 
                 confirmacionFechasPod::create([
                    'confirmacion_dt_id' => $request['confirmacion'],
                    'fecha_pod_id' => 4,
                    'fecha' => $request['fecha']
                 ]);
                break;
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(confirmacionFechasPod $confirmacionFechasPod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(confirmacionFechasPod $confirmacionFechasPod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, confirmacionFechasPod $confirmacionFechasPod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(confirmacionFechasPod $confirmacionFechasPod)
    {
        //
    }
}
