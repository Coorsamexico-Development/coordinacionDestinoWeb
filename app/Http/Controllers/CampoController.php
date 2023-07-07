<?php

namespace App\Http\Controllers;

use App\Models\Campo;
use App\Models\DtCampoValor;
use App\Models\Statu;
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
    //Una vez que se crea un valor, se crea un registro dt_campo_valor
    public function indexApi(Request $request)
    {
       if($request->has('status_id'))
       {
       
      $status_padre = Statu::select('status.status_padre')
        ->where('status.id','=', $request['status_id'])
        ->first();

        return  
           Campo::select('campos.*','tipos_campos.nombre as tipo_campo')
           ->where('status_id','=', $status_padre->status_padre)
           ->join('tipos_campos','campos.tipo_campo_id','tipos_campos.id')
           ->get();
       }
    }
}
