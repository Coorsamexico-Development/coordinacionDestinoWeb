<?php

namespace App\Http\Controllers;

use App\Models\ConfirmacionDt;
use Illuminate\Http\Request;

class ConfirmacionDtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        request()->validate([
            'plataforma_id' => ['required'],
            'ubicacion_id' => ['required'],
            'status_id' => ['required']
        ]);
        
        return ConfirmacionDt::select(
            'confirmacion_dts.*',
            'dts.referencia_dt',
            'linea_transportes.nombre as linea_transporte',
            'status.color'
        )
        ->where('status.status_padre','=',$request['status_id'])
        ->where('confirmacion_dts.ubicacion_id','=',$request['ubicacion_id'])
        ->where('confirmacion_dts.plataforma_id','=',$request['plataforma_id'])
        ->join('dts','confirmacion_dts.dt_id','dts.id')
        ->join('linea_transportes', 'confirmacion_dts.linea_transporte_id', 'linea_transportes.id')
        ->join('status', 'confirmacion_dts.status_id', 'status.id')
        ->paginate(5);
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
    public function show(ConfirmacionDt $confirmacionDt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConfirmacionDt $confirmacionDt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConfirmacionDt $confirmacionDt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConfirmacionDt $confirmacionDt)
    {
        //
    }

    //Functions API
    public function indexApi(Request $request)
    {
        $confirmacionesDts = null;
        if(request()->has('search'))
        {
           if($request['search'] === null)
           {
            $confirmacionesDts = ConfirmacionDt::select(
                'confirmacion_dts.*',
                'dts.referencia_dt',
                'linea_transportes.nombre as linea_transporte',
                'status.nombre as status',
                'status.color'
               )
             /* 
            ->where('status.status_padre','=',$request['status_id'])
            ->where('confirmacion_dts.ubicacion_id','=',$request['ubicacion_id'])
            ->where('confirmacion_dts.plataforma_id','=',$request['plataforma_id'])
            */
            ->join('dts','confirmacion_dts.dt_id','dts.id')
            ->join('linea_transportes', 'confirmacion_dts.linea_transporte_id', 'linea_transportes.id')
            ->join('status', 'confirmacion_dts.status_id', 'status.id');
           }
           else
           {
            $confirmacionesDts = ConfirmacionDt::select(
                'confirmacion_dts.*',
                'dts.referencia_dt',
                'linea_transportes.nombre as linea_transporte',
                'status.nombre as status',
                'status.color'
               )
             /* 
            ->where('status.status_padre','=',$request['status_id'])
            ->where('confirmacion_dts.ubicacion_id','=',$request['ubicacion_id'])
            ->where('confirmacion_dts.plataforma_id','=',$request['plataforma_id'])
            */
            ->join('dts','confirmacion_dts.dt_id','dts.id')
            ->join('linea_transportes', 'confirmacion_dts.linea_transporte_id', 'linea_transportes.id')
            ->join('status', 'confirmacion_dts.status_id', 'status.id')
            ->where('dts.referencia_dt','LIKE', '%'.$request['search'].'%');
           }
        }
        
        return $confirmacionesDts->get();
    }

    public function changeToRiesgo (Request $request)
    {
         ConfirmacionDt::where('id','=',$request['id'])
         ->update([
            'confirmacion_dts.status_id' => 6
         ]);
    }
}
