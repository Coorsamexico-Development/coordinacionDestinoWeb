<?php

namespace App\Http\Controllers;

use App\Models\ConfirmacionDt;
use App\Models\Oc;
use Illuminate\Http\Request;

class OcController extends Controller
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
        $request->validate([ //validaciones
            'ocs' => 'required|unique:ocs',
            'confirmacion' => 'required'
        ]);

        $confirmacion = ConfirmacionDt::select('confirmacion_dts.*')
        ->where('confirmacion_dts.confirmacion','=',$request['confirmacion'])
        ->first();

        if (count($request['ocs']) > 0) 
        {
           for ($i=0; $i < count($request['ocs']) ; $i++) 
           { 

            $oc = $request['ocs'][$i];

             Oc::updateOrcreate([
               'confirmacion_dt_id' => $confirmacion['id'],
               'referencia' => $oc['referencia']
             ]);
           }
        }

      return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Oc $oc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Oc $oc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Oc $oc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Oc $oc)
    {
        //
    }

    public function consultarOcs(Request $request)
    {
        $confirmacion = ConfirmacionDt::select('confirmacion_dts.*')
        ->where('confirmacion_dts.confirmacion','=',$request['confirmacion'])
        ->first();

        return $confirmacion['id'];

        return Oc::select('ocs.*')
        ->where('ocs.confirmacion_dt_id','=', $confirmacion['id'])
        ->get();
    }
}
