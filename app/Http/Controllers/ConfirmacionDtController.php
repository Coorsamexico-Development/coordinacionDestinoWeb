<?php

namespace App\Http\Controllers;

use App\Models\Campo;
use App\Models\ConfirmacionDt;
use App\Models\Dt;
use App\Models\DtCampoValor;
use App\Models\HorasHistorico;
use App\Models\Oc;
use App\Models\Plataforma;
use App\Models\Statu;
use App\Models\StatusDt;
use App\Models\Ubicacione;
use App\Models\Valor;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\Events\NewNotification;

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
            'status_id' => ['required'],
        ]);

        
       $confirmaciones =  ConfirmacionDt::select(
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
        ->where('confirmacion_dts.plataforma_id','=',$request['plataforma_id']);

        if($request->has("busqueda"))
        {
          if($request['busqueda'] !== null)
          {
            $search = strtr($request->busqueda, array("'" => "\\'", "%" => "\\%"));
            $confirmaciones->where("confirmacion_dts.confirmacion", "LIKE", "%" . $search . "%")
            ->orWhere("dts.referencia_dt", "LIKE", "%" . $search . "%");
          }
        }

      return  $confirmaciones->paginate(5);
    }

    public function getConfirmacionByStatus (Request $request)
    {
          request()->validate([
            'plataforma_id' => ['required'],
            'ubicacion_id' => ['required'],
            'status_id' => ['required'],
        ]);
     
        
       $confirmaciones =  ConfirmacionDt::select(
            'confirmacion_dts.*',
            'dts.referencia_dt',
            'linea_transportes.nombre as linea_transporte',
            'status.color',
            'status.nombre as status'
        )
        ->join('dts','confirmacion_dts.dt_id','dts.id')
        ->join('linea_transportes', 'confirmacion_dts.linea_transporte_id', 'linea_transportes.id')
        ->join('status', 'confirmacion_dts.status_id', 'status.id')
        ->where('status.id','=',$request['status_id'])
        ->where('confirmacion_dts.ubicacion_id','=',$request['ubicacion_id'])
        ->where('confirmacion_dts.plataforma_id','=',$request['plataforma_id']);
     
        if($request->has("busqueda"))
        {
          if($request['busqueda'] !== null)
          {
            $search = strtr($request->busqueda, array("'" => "\\'", "%" => "\\%"));
            $confirmaciones->where("confirmacion_dts.confirmacion", "LIKE", "%" . $search . "%")
            ->orWhere("dts.referencia_dt", "LIKE", "%" . $search . "%");
          }
        }
     
      return  $confirmaciones->paginate(5);
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
            'status.color',
            'plataformas.nombre as plataforma'
           )
           ->join('dts','confirmacion_dts.dt_id','dts.id')
           ->join('linea_transportes', 'confirmacion_dts.linea_transporte_id', 'linea_transportes.id')
           ->join('status', 'confirmacion_dts.status_id', 'status.id')
           ->join('plataformas', 'confirmacion_dts.plataforma_id','plataformas.id')

           ->where('confirmacion_dts.cerrado','=',0);
           
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
                ->where('confirmacion_dts.cerrado','=',0);
                //->where('confirmacion_dts.ubicacion_id','=',$request['ubicacion_id']);
            }]
            )
           ->get();
        //return   $confirmacionesDts->get();
        return ['dts' => $confirmacionesDts->get() , 'plataformas'=>$plataformas];
    }

    public function changeToRiesgo (Request $request)
    {
          date_default_timezone_set('America/Mexico_City');
          $fecha_actual = getdate();
          $hora_actual = ($fecha_actual['hours']-1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
          $newFecha = $fecha_actual['year'].'-'.$fecha_actual['mon'].'-'.$fecha_actual['mday'].' '.$hora_actual; 

          ConfirmacionDt::where('id','=',$request['id'])
            ->update([
               'confirmacion_dts.status_id' => 5,
               'confirmacion_dts.updated_at' =>$newFecha,
            ]);
   
          StatusDt::where('id','=',$request['id'])
          ->update([
            'activo' => 0
          ]);
          //Creamos el primer registro en la tabla de historico
          StatusDt::updateOrCreate([
           'confirmacion_dt_id' => $request['id'],
           'status_id' => 5,
           'activo' => 1,
           'created_at' => $newFecha,
           'updated_at' =>$newFecha,
         ]);

         $confrimacionDt = ConfirmacionDt::select('confirmacion_dts.*')
         ->where('confirmacion_dts.id',$request['id'])->first();

         broadcast(new NewNotification($confrimacionDt->id))->toOthers();
    }

    public function changePorRecibir (Request $request) //o a tiempo
    {
      
          date_default_timezone_set('America/Mexico_City');
          $fecha_actual = getdate();
          $hora_actual = ($fecha_actual['hours']-1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
          $newFecha = $fecha_actual['year'].'-'.$fecha_actual['mon'].'-'.$fecha_actual['mday'].' '.$hora_actual; 

            ConfirmacionDt::where('id','=',$request['id'])
            ->update([
               'confirmacion_dts.status_id' => 4,
               'confirmacion_dts.updated_at' =>$newFecha,
            ]);
   
          StatusDt::where('id','=',$request['id'])
          ->update([
            'activo' => 0
          ]);
          //Creamos el primer registro en la tabla de historico
          StatusDt::updateOrCreate([
           'confirmacion_dt_id' => $request['id'],
           'status_id' => 4,
           'activo' => 1,
           'created_at' => $newFecha,
           'updated_at' =>$newFecha,
         ]);
    }

    public function changeToEnEspera (Request $request) 
    {
      date_default_timezone_set('America/Mexico_City');
      $fecha_actual = getdate();
      $hora_actual = ($fecha_actual['hours']-1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
      $newFecha = $fecha_actual['year'].'-'.$fecha_actual['mon'].'-'.$fecha_actual['mday'].' '.$hora_actual; 

          ConfirmacionDt::where('id','=',$request['id'])
          ->update([
             'confirmacion_dts.status_id' => 9,
             'confirmacion_dts.updated_at' =>$newFecha,
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
         'created_at' => $newFecha,
         'updated_at' =>$newFecha,
       ]);
    }

    public function changeToEnDocumentacion (Request $request) 
    {

      date_default_timezone_set('America/Mexico_City');
      $fecha_actual = getdate();
      $hora_actual = ($fecha_actual['hours']-1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
      $newFecha = $fecha_actual['year'].'-'.$fecha_actual['mon'].'-'.$fecha_actual['mday'].' '.$hora_actual; 

          ConfirmacionDt::where('id','=',$request['id'])
          ->update([
             'confirmacion_dts.status_id' => 8,
             'confirmacion_dts.updated_at' =>$newFecha,
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
         'created_at' => $newFecha,
         'updated_at' =>$newFecha,
       ]);
    }

    public function changeToDescarga (Request $request)
    {
      date_default_timezone_set('America/Mexico_City');
      $fecha_actual = getdate();
      $hora_actual = ($fecha_actual['hours']-1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
      $newFecha = $fecha_actual['year'].'-'.$fecha_actual['mon'].'-'.$fecha_actual['mday'].' '.$hora_actual; 

      ConfirmacionDt::where('id','=',$request['id'])
          ->update([
             'confirmacion_dts.status_id' => 11,
             'confirmacion_dts.updated_at' =>$newFecha,
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
         'created_at' => $newFecha,
         'updated_at' =>$newFecha,
       ]);
    }

    public function changeEnrrampado(Request $request)
    {
      date_default_timezone_set('America/Mexico_City');
      $fecha_actual = getdate();
      $hora_actual = ($fecha_actual['hours']-1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
      $newFecha = $fecha_actual['year'].'-'.$fecha_actual['mon'].'-'.$fecha_actual['mday'].' '.$hora_actual; 

      ConfirmacionDt::where('id','=',$request['id'])
      ->update([
         'confirmacion_dts.status_id' => 8,
         'confirmacion_dts.updated_at' =>$newFecha,
      ]);

       StatusDt::where('id','=',$request['id'])
       ->update([
         'activo' => 0
       ]);
       //Creamos el primer registro en la tabla de historico
      $newStatus = StatusDt::updateOrCreate([
        'confirmacion_dt_id' => $request['id'],
        'status_id' => 8,
        'activo' => 1,
        'created_at' => $newFecha,
        'updated_at' =>$newFecha,
      ]);

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

       //return $request;

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
        $hoy = date("Y-m");  
        //return $hoy;
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
       
       $confirmacion_Dt = ConfirmacionDt::select('confirmacion_dts.*')
       ->where('confirmacion_dts.confirmacion','=',$request['params']['confirmacion'])
       ->first();

       $ocs = Oc::select('ocs.*')
       ->with('incidencias')
       ->where('ocs.confirmacion_dt_id','=',$confirmacion_Dt['id'])
       ->get();
     
       //return $totalIncidencias;

       $status = Statu::select('status.*')
       ->where('status.id','=',$request['params']['status_id'])
       ->first();
     
       $status_dt = StatusDt::select('status_dts.*')
       ->where('status_dts.status_id','=',$status['id'])
       ->where('status_dts.confirmacion_dt_id','=',$confirmacion_Dt['id'])
       //->where('status_dts.activo','=',1)
       ->first();

       HorasHistorico::updateOrCreate([
         'hora_id' => 6, //es la hr de folios
         'status_dts_id' => $status_dt['id'],
         'hora' => $request['params']['horaImpresion']
       ]);

       //Creamos el guardado de los valores
       for ($i=0; $i < count($request['params']['valores']) ; $i++)
       { 
          $valor = $request['params']['valores'][$i];
          //Buscamos el dt_campo_valor
          $dt_campo_valor = DtCampoValor::select('dt_campo_valors.*')
          ->where('dt_campo_valors.confirmacion_id','=',$confirmacion_Dt['id'])
          ->where('dt_campo_valors.campo_id','=', $valor['campo_id'])
          ->first();

          if($dt_campo_valor !== null)
          {
              //creamos los valores
              $newValor = Valor::updateOrCreate([
                'valor' => $valor['value'],
                'dt_campo_valor_id' => $dt_campo_valor['id'],
                'user_id' => $request['params']['usuario']
             ]);
          }
          else
          {
            $newDt_campo_valor = DtCampoValor::create([
              'confirmacion_id' =>  $confirmacion_Dt['id'],
              'campo_id' => $valor['campo_id']
            ]);

            $newValor = Valor::updateOrCreate([
              'valor' => $valor['value'],
              'dt_campo_valor_id' => $newDt_campo_valor['id'],
              'user_id' => $request['params']['usuario']
           ]);
          }


       }

      //debemos validar si salio con alguna incidencia o no para eso recorremos las OCS y con esos sus incidencias
      //si se llega a encontrar alguna ya se considera liberacion con incidencia}
          //recorremos las ocs para ver si hay incidencias
          $totalIncidencias = [];
          for ($x=0; $x < count($ocs) ; $x++) 
          { 
             $oc = $ocs[$x];
             if(count($oc['incidencias']) > 0)
             {
               array_push($totalIncidencias, $oc['incidencias']);
             }
          }
   
          if(count($totalIncidencias) > 0) //si hay al menos alguna incidencia
          {
             ConfirmacionDt::where('id','=',$confirmacion_Dt['id'])
             ->update([
                'confirmacion_dts.status_id' => 11 //se libera con incidencia
             ]);
     
              StatusDt::where('confirmacion_dt_id','=',$confirmacion_Dt['id']) //todos los status que tengan esa confirmacion se pasaran a inactivos
              ->update([
                'activo' => 0
              ]);
              //Creamos el primer registro en la tabla de historico
             $newStatus = StatusDt::updateOrCreate([
               'confirmacion_dt_id' => $confirmacion_Dt['id'],
               'status_id' => 11,
               'activo' => 1,
             ]);
          }
          else
          {
              ConfirmacionDt::where('id','=',$confirmacion_Dt['id'])
              ->update([
                 'confirmacion_dts.status_id' => 10, //se libera sin incidencia
                 'confirmacion_dts.cerrado' => 1
              ]);
   
               StatusDt::where('confirmacion_dt_id','=',$confirmacion_Dt['id']) //todos los status que tengan esa confirmacion se pasaran a inactivos
               ->update([
                 'activo' => 0
               ]);
               //Creamos el primer registro en la tabla de historico
              $newStatus = StatusDt::updateOrCreate([
                'confirmacion_dt_id' => $confirmacion_Dt['id'],
                'status_id' => 10,
                'activo' => 1,
              ]);
          }
 
  }

  public function saveDocEnrrampe(Request $request)
  {
       //Si existe un documento hay que guardarlo respectivamente
       if($request['file'] !== null)
       {
        if(is_file(($request['file'])))
        {
          $file = request('file');
          $nombre_original = $file->getClientOriginalName();
          $ruta_file = $file->storeAs('docs', $nombre_original, 'gcs');
          $urlFile = Storage::disk('gcs')->url($ruta_file);

          //comprobamos
          $dt_campo = DtCampoValor::select( //buscaremos el valor del archivo o la relacion
            'dt_campo_valors.*'
            )
            ->where('dt_campo_valors.confirmacion_id','=', $request['confirmacion_id'])
            ->where('dt_campo_valors.campo_id','=', $request['tipo_campo_file'])
            ->first();

            if($dt_campo == null)
            {
                $dt_campo = DtCampoValor::create(
                [
                   'confirmacion_id' => $request['confirmacion_id'],
                   'campo_id' =>$request['tipo_campo_file']
                ]);

                //Hay que encontrar todos los valores anteriores para desactivarlos
                //y crear uno nuevo
                $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo['id'])
                ->update(['activo' => 0]);
                //Crea nuevo valor en la tabla de valores
                $newValor = Valor::create([
                    'valor' => $urlFile,
                    'dt_campo_valor_id' => $dt_campo->id,
                    'user_id' => $request['usuario']
                ]);
            }
            else
            {
              $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo['id'])
              ->update(['activo' => 0]);
              //Crea nuevo valor en la tabla de valores
              $newValor = Valor::create([
                  'valor' => $urlFile,
                  'dt_campo_valor_id' => $dt_campo->id,
                  'user_id' => $request['usuario']
              ]);  
            }
        }
       }


  }

  public function firmasLiberacion (Request $request)
  {
     //creacion del PDF el cual se debe guardar en confirmacion_dt
     $pdf = App::make('dompdf.wrapper');
     //return $request['params'];
     $status = $request['params']['status'];
     $firmas = $request['params']['firmas'];
     
     $confirmacion_dt = ConfirmacionDt::select('confirmacion_dts.*')
    ->where('confirmacion_dts.confirmacion','=',$request['params']['confirmacion'])
    ->first();
    
     //buscamos el dt
     $dt = Dt::select( 
      'dts.referencia_dt'
      )
      ->where('dts.id','=',$request['params']['dt'])
      ->first();


     //Consultamos todos los campos de status por confirmacion
     $statusByConfirmacion = StatusDt::select(
      'status.id as status_id',
      'status.status_padre as status_padre_id',
      'status.nombre as status_name',
      'status.color as color',
      'status_dts.updated_at as status_dt_updated_at',
      'status_dts.created_at as status_dt_created_at'
    )
    ->with([
      'status' => function ($query) 
        {
          return  $query->select(
            'status.*'
          )
          ->with([
            'campos2' => function ($query1) 
            {
               return $query1->select(
                'campos.*',
                'tipos_campos.nombre as tipo_campo'
               )
               ->join('tipos_campos','campos.tipo_campo_id','tipos_campos.id');
            }
          ]);
        }
      ])
    ->join('status','status_dts.status_id','status.id')
    ->where('confirmacion_dt_id','=', $confirmacion_dt['id'])
    ->distinct('status.id')
    ->get();

    //Consultamos valores
    $valors = Valor::select('valors.*','campos.id as campo_id','status.id as status_id')
    ->join('dt_campo_valors','valors.dt_campo_valor_id','dt_campo_valors.id')
    ->join('confirmacion_dts', 'dt_campo_valors.confirmacion_id','confirmacion_dts.id')
    ->join('campos','dt_campo_valors.campo_id','campos.id')
    ->join('status','campos.status_id','status.id')
    ->where('valors.activo','=', 1)
    ->where('confirmacion_dts.id','=',$confirmacion_dt['id'])
    ->distinct('valors.id')
    ->get();

    $ocs = Oc::select('ocs.*')
    ->get();

      //seteamos la data en el pdf para la plantilla
      $data = [
        'confirmacion' =>  $request['params']['confirmacion'],
        'dt' =>  $dt['referencia_dt'],//$dt['referencia_dt'],
        'status_dt' => $statusByConfirmacion,
        'title' =>  $request['params']['confirmacion'].'_'.date('Y-m-d H-m'), // $request['confirmacion'].'_'.now(),
        'cita' =>  $confirmacion_dt['cita'], //$confirmacion_dt['cita']
        'valors' => $valors,
        'firmas' => $firmas
      ];

      $options = new Options();
      $pdf->set_option('isRemoteEnabled', true);
      $pdf->loadView('pdfs.plantilla_confirmacion', $data);
                 
      //guardamos en storage
       $ruta_pdf  =  Storage::disk('gcs') //guardamos en google
      ->put(
       'pdfs/'.$request['params']['confirmacion'].'_'.date('Y-m-d').'_'.date('h-i').'.pdf',
        $pdf->output()
      );
     
      $urlPdf = Storage::disk('gcs')->url('pdfs/'.$request['params']['confirmacion'].'_'.date('Y-m-d').'_'.date('h-i').'.pdf');
      //Seteamos el documento en la BD y cambiamos status a liberacion de incidencia
      
      $setPDF = ConfirmacionDt::where('confirmacion','=',$request['params']['confirmacion'])
      ->update([
        'pdf' => $urlPdf,
        'cerrado' => 1
      ]);
      

    return 'ok';
  }

  public function getTelephone (Request $request)
  {
     if($request['confirmacion'])
     {
       $confirmacion_dt = ConfirmacionDt::select('confirmacion_dts.*')
       ->where('confirmacion_dts.confirmacion','=',$request['confirmacion'])
       ->first(); 

       //buscamos el campo del telefono
       $dt_campo_valor = DtCampoValor::select('dt_campo_valors.*')
       ->where('dt_campo_valors.confirmacion_id','=',$confirmacion_dt['id'])
       ->where('dt_campo_valors.campo_id','=',5)
       ->first();

       return  $telefono = Valor::select('valors.*')
       ->where('valors.dt_campo_valor_id','=',$dt_campo_valor['id'])
       ->first();
     }
  }

  public function saveDocPOD (Request $request)
  {

    if($request->has('document'))
    {
      $nombre =  $request['document']->getClientOriginalName();
      $rutaDoc = $request['document']->storeAs('documentosPOD', $nombre ,'gcs');
      $urlDoc = Storage::disk('gcs')->url($rutaDoc);

      ConfirmacionDt::where(
        'id','=', $request['confirmacion']
      )
      ->update([
         'documetoPOD' => $urlDoc
      ]);
    }
  }
  
}
