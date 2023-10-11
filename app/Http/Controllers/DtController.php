<?php

namespace App\Http\Controllers;

use App\Models\ConfirmacionDt;
use App\Models\Dt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $viajes = ConfirmacionDt::select('confirmacion_dts.*',
        'dts.referencia_dt')
        ->join('dts','confirmacion_dts.dt_id','dts.id');

        if ($request->has("busqueda")) 
        {
          if($request['busqueda'] !== null)
          {
            $search = strtr($request->busqueda, array("'" => "\\'", "%" => "\\%"));
            $viajes->where("confirmacion_dts.confirmacion", "LIKE", "%" . $search . "%")
            ->orWhere("dts.referencia_dt", "LIKE", "%" . $search . "%");
          }
        }

        $viajes->where('confirmacion_dts.status_id','=',10);
        $viajes->orwhere('confirmacion_dts.status_id','=',11);

        return Inertia::render('Viajes/Viajes.Index',[
            'viajes' => fn () =>  $viajes->paginate(5)
        ]);
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
    public function show(Dt $dt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dt $dt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dt $dt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dt $dt)
    {
        //
    }
}
