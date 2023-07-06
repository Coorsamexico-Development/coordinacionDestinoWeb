<?php

namespace App\Http\Controllers;

use App\Models\Campo;
use App\Models\DtCampoValor;
use Illuminate\Http\Request;

class CampoController extends Controller
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
    public function show(Campo $campo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campo $campo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campo $campo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campo $campo)
    {
        //
    }

    //<Consulta de campos para API
    public function indexApi(Request $request)
    {
       if($request->has('dt_id'))
       {
        return   DtCampoValor::select('dt_campo_valors.*',
                'campos.nombre as campo', 'campos.status_id as status',
                'tipos_campos.nombre as tipo_campo'
                )
          ->where('dt_campo_valors.dt_id','=',$request['dt_id'])
          ->join('campos','dt_campo_valors.campo_id', 'campos.id')
          ->join('tipos_campos','campos.tipo_campo_id', 'tipos_campos.id')
          ->get();
       }
    }
}
