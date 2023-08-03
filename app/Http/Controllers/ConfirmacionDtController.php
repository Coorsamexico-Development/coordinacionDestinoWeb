<?php

namespace App\Http\Controllers;

use App\Models\ConfirmacionDt;
use App\Models\Plataforma;
use App\Models\StatusDt;
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
            'status.color',
            'status.nombre as status'
        )
        ->join('dts','confirmacion_dts.dt_id','dts.id')
        ->join('linea_transportes', 'confirmacion_dts.linea_transporte_id', 'linea_transportes.id')
        ->join('status', 'confirmacion_dts.status_id', 'status.id')
        ->where('status.status_padre','=',$request['status_id'])
        ->where('confirmacion_dts.ubicacion_id','=',$request['ubicacion_id'])
        ->where('confirmacion_dts.plataforma_id','=',$request['plataforma_id'])
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
        $confirmacionesDts = ConfirmacionDt::select(
            'confirmacion_dts.*',
            'dts.referencia_dt',
            'linea_transportes.nombre as linea_transporte',
            'status.nombre as status',
            'status.color'
           )
           ->join('dts','confirmacion_dts.dt_id','dts.id')
           ->join('linea_transportes', 'confirmacion_dts.linea_transporte_id', 'linea_transportes.id')
           ->join('status', 'confirmacion_dts.status_id', 'status.id');
           
           if($request->has('ubicacion_id'))
           {
             if($request['ubicacion_id'] !== null)
             {
                $confirmacionesDts->where('confirmacion_dts.ubicacion_id','LIKE','%'.$request['ubicacion_id'].'%');
             }
           } 
           if($request->has('search'))
           {
             if($request['search'] !== null)
             {
                $confirmacionesDts->where('dts.referencia_dt','LIKE','%'.$request['search'].'%');
             }
           } 

           if($request->has('plataforma_id'))
           {
             if($request['plataforma_id'] !== null)
             {
                $confirmacionesDts->where('confirmacion_dts.plataforma_id','LIKE','%'.$request['plataforma_id'].'%');
             }
           } 

           $plataformas = Plataforma::select('plataformas.*')
           ->with(['confirmacionesDts'=> function ($query) use($request)
            {
                $query->select(
                    'confirmacion_dts.*',
                )
                ->where('confirmacion_dts.ubicacion_id','=',$request['ubicacion_id']);
            }]
            )
           ->get();
        //return   $confirmacionesDts->get();
        return ['dts' => $confirmacionesDts->get() , 'plataformas'=>$plataformas];
    }

    public function changeToRiesgo (Request $request)
    {
          ConfirmacionDt::where('id','=',$request['id'])
            ->update([
               'confirmacion_dts.status_id' => 6
            ]);
   
          StatusDt::where('id','=',$request['id'])
          ->update([
            'activo' => 0
          ]);
          //Creamos el primer registro en la tabla de historico
          StatusDt::updateOrCreate([
           'confirmacion_dt_id' => $request['id'],
           'status_id' => 6,
           'activo' => 1,
         ]);

    }

    public function changePorRecibir (Request $request)
    {
            ConfirmacionDt::where('id','=',$request['id'])
            ->update([
               'confirmacion_dts.status_id' => 7
            ]);
   
          StatusDt::where('id','=',$request['id'])
          ->update([
            'activo' => 0
          ]);
          //Creamos el primer registro en la tabla de historico
          StatusDt::updateOrCreate([
           'confirmacion_dt_id' => $request['id'],
           'status_id' => 7,
           'activo' => 1,
         ]);
    }

    public function changeToEnEspera (Request $request) 
    {
          ConfirmacionDt::where('id','=',$request['id'])
          ->update([
             'confirmacion_dts.status_id' => 9
          ]);

        StatusDt::where('id','=',$request['id'])
        ->update([
          'activo' => 0
        ]);
        //Creamos el primer registro en la tabla de historico
        StatusDt::updateOrCreate([
         'confirmacion_dt_id' => $request['id'],
         'status_id' => 9,
         'activo' => 1,
       ]);
    }

    public function changeToEnDocumentacion (Request $request) 
    {
          ConfirmacionDt::where('id','=',$request['id'])
          ->update([
             'confirmacion_dts.status_id' => 8
          ]);
    
        StatusDt::where('id','=',$request['id'])
        ->update([
          'activo' => 0
        ]);
        //Creamos el primer registro en la tabla de historico
        StatusDt::updateOrCreate([
         'confirmacion_dt_id' => $request['id'],
         'status_id' => 8,
         'activo' => 1,
       ]);
    }

    public function changeToDescarga (Request $request)
    {
      ConfirmacionDt::where('id','=',$request['id'])
          ->update([
             'confirmacion_dts.status_id' => 11
          ]);
    
        StatusDt::where('id','=',$request['id'])
        ->update([
          'activo' => 0
        ]);
        //Creamos el primer registro en la tabla de historico
        StatusDt::updateOrCreate([
         'confirmacion_dt_id' => $request['id'],
         'status_id' => 11,
         'activo' => 1,
       ]);
    }

    public function changeEnrrampado(Request $request)
    {
      ConfirmacionDt::where('id','=',$request['id'])
      ->update([
         'confirmacion_dts.status_id' => 10
      ]);

    StatusDt::where('id','=',$request['id'])
    ->update([
      'activo' => 0
    ]);
    //Creamos el primer registro en la tabla de historico
    StatusDt::updateOrCreate([
     'confirmacion_dt_id' => $request['id'],
     'status_id' => 10,
     'activo' => 1,
   ]);
    }

  public function getPDF (Request $request)
  {
      return ConfirmacionDt::select('confirmacion_dts.*')
      ->where('id','=',$request['id'])
      ->first();
  }
}
