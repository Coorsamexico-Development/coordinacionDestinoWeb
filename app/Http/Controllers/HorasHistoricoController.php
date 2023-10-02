<?php

namespace App\Http\Controllers;

use App\Models\ConfirmacionDt;
use App\Models\HorasHistorico;
use App\Models\Statu;
use App\Models\StatusDt;
use Illuminate\Http\Request;

class HorasHistoricoController extends Controller
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
    }

    /**
     * Display the specified resource.
     */
    public function show(HorasHistorico $horasHistorico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HorasHistorico $horasHistorico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HorasHistorico $horasHistorico)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HorasHistorico $horasHistorico)
    {
        //
    }

    public function savehrFolios(Request $request)
    {
      $confirmacion_Dt = ConfirmacionDt::select('confirmacion_dts.*')
       ->where('confirmacion_dts.confirmacion','=',$request['confirmacion'])
       ->first();

       $status = Statu::select('status.*')
       ->where('status.id','=',$request['status_id'])
       ->first();
     
       $status_dt = StatusDt::select('status_dts.*')
       ->where('status_dts.status_id','=',$status['id'])
       ->where('status_dts.confirmacion_dt_id','=',$confirmacion_Dt['id'])
       ->where('status_dts.activo','=',1)
       ->first();

       date_default_timezone_set('America/Mexico_City');
       $fecha_actual = getdate();
       $hora_actual = $fecha_actual['hours'] . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
 

       HorasHistorico::updateOrCreate([
         'hora_id' => 4, //es la hr de folios
         'status_dts_id' => $status_dt['id'],
         'hora' => $hora_actual
       ]);

    }
}
