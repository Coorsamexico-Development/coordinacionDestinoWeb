<?php

namespace App\Http\Controllers;

use App\Models\ConfirmacionDt;
use App\Models\HorasHistorico;
use App\Models\Plataforma;
use App\Models\Statu;
use App\Models\StatusDt;
use App\Models\Ubicacione;
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
      $newStatus = StatusDt::updateOrCreate([
        'confirmacion_dt_id' => $request['id'],
        'status_id' => 10,
        'activo' => 1,
      ]);

      date_default_timezone_set('America/Mexico_City');
      $fecha_actual = getdate();
      $hora_actual = $fecha_actual['hours'] . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];

       HorasHistorico::create([
         'hora_id' => 5,
         'status_dts_id' => $newStatus['id'],
         'hora' => $hora_actual
       ]);
      
    }

  public function getPDF (Request $request)
  {
      return ConfirmacionDt::select('confirmacion_dts.*')
      ->where('id','=',$request['id'])
      ->first();
  }

  public function consultarConfirmaciones (Request $request)
  {

      $ubicacion = Ubicacione::select('ubicaciones.*')
      ->where('ubicaciones.nombre_ubicacion','=',$request['ubicacion'])
      ->first();

      $status = Statu::select('status.*')
      ->where('status.nombre','=',$request['status'])
      ->first();

      if($request['fecha'])
      {
        return $confirmaciones = 
        StatusDt::select( 'confirmacion_dts.id',
        'confirmacion_dts.confirmacion',
        'confirmacion_dts.pdf',
        'confirmacion_dts.cita',
        'confirmacion_dts.numero_cajas',
        'confirmacion_dts.pdf',
        'confirmacion_dts.dt_id as dt_id',
        'dts.referencia_dt as dt',
        'linea_transportes.nombre as linea_transporte',
        'plataformas.nombre as plataforma')
        ->join('confirmacion_dts','status_dts.confirmacion_dt_id','confirmacion_dts.id')
        ->join('dts','confirmacion_dts.dt_id','dts.id')
        ->join('linea_transportes','confirmacion_dts.linea_transporte_id','linea_transportes.id')
        ->join('plataformas','confirmacion_dts.plataforma_id','plataformas.id')
        ->where('status_dts.status_id','=', $status['id'])
        ->where('confirmacion_dts.ubicacion_id','=', $ubicacion['id'])
        ->where('confirmacion_dts.cita','LIKE','%'.$request['fecha'].'%')
        ->distinct('status_dts.confirmacion_dt_id')
        ->get();
        
      /*$confirmaciones = ConfirmacionDt::select(
              'confirmacion_dts.id',
              'confirmacion_dts.confirmacion',
              'confirmacion_dts.pdf',
              'confirmacion_dts.cita',
              'confirmacion_dts.numero_cajas',
              'confirmacion_dts.pdf',
              'confirmacion_dts.dt_id as dt_id',
              'dts.referencia_dt as dt',
              'linea_transportes.nombre as linea_transporte',
              'plataformas.nombre as plataforma'
              )
       ->where('confirmacion_dts.status_id','=', $status['id'])
       ->where('confirmacion_dts.ubicacion_id','=', $ubicacion['id'])
       ->where('confirmacion_dts.cita','LIKE','%'.$request['fecha'].'%')
       ->join('dts','confirmacion_dts.dt_id','dts.id')
       ->join('linea_transportes','confirmacion_dts.linea_transporte_id','linea_transportes.id')
       ->join('plataformas','confirmacion_dts.plataforma_id','plataformas.id')
       ->get();
       */
      }
      else
      {
        $hoy = date("Y-m-d");  
        return $confirmaciones = 
        StatusDt::select( 'confirmacion_dts.id',
        'confirmacion_dts.confirmacion',
        'confirmacion_dts.pdf',
        'confirmacion_dts.cita',
        'confirmacion_dts.numero_cajas',
        'confirmacion_dts.pdf',
        'confirmacion_dts.dt_id as dt_id',
        'dts.referencia_dt as dt',
        'linea_transportes.nombre as linea_transporte',
        'plataformas.nombre as plataforma')
        ->join('confirmacion_dts','status_dts.confirmacion_dt_id','confirmacion_dts.id')
        ->join('dts','confirmacion_dts.dt_id','dts.id')
        ->join('linea_transportes','confirmacion_dts.linea_transporte_id','linea_transportes.id')
        ->join('plataformas','confirmacion_dts.plataforma_id','plataformas.id')
        ->where('status_dts.status_id','=', $status['id'])
        ->where('confirmacion_dts.ubicacion_id','=', $ubicacion['id'])
        ->where('confirmacion_dts.cita','LIKE','%'.$hoy.'%')
        ->distinct('status_dts.confirmacion_dt_id')
        ->get();
      }
  }

  public function valoresLiberacion (Request $request)
  {
      return $request['params'];
       $confirmacion_Dt = ConfirmacionDt::select('confirmacion_dts.*')
       ->where('confirmacion_dts.confirmacion','=',[$request['confirmacion']])
       ->first();

       $status = Statu::select('status.*')
       ->where('status.id','=',$request['status_id'])
       ->first();
     
       $status_dt = StatusDt::select('status_dts.*')
       ->where('status_dts.status_id','=',$status['id'])
       ->where('status_dts.confirmacion_dt_id','=',$confirmacion_Dt['id'])
       ->where('status_dts.activo','=',1)
       ->first();


       HorasHistorico::updateOrCreate([
         'horas_historicos.hora_id' => 6, //es la hr de folios
         'horas_historicos.status_dts_id' => $status_dt['id'],
         'horas_historicos.hora' => $request['params']['horaImpresion']
       ]);



  }
}
