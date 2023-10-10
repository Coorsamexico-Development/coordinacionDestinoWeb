<?php

namespace App\Http\Controllers;

use App\Models\Campo;
use App\Models\ConfirmacionDt;
use App\Models\Dt;
use App\Models\DtCampoValor;
use App\Models\HorasHistorico;
use App\Models\Statu;
use App\Models\StatusDt;
use App\Models\Valor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Google\Cloud\Storage\StorageClient;

class ValorController extends Controller
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
    public function show(Valor $valor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Valor $valor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Valor $valor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Valor $valor)
    {
        //
    }

    public function valoresApi (Request $request)  //pantalla de llegada registra hr de llegada
    {

      $data = $request['params']['data'];
      if($request['params']['tipo'] == 'guardar' )
      {
          //Si es guardado envia los datos pero no cambie el status
          //Se recorren los datos y se extraen los campos, al recorrer el ciclo, se insertaran en la BD
          for ($i=0; $i < count($data) ; $i++) 
          { 
            $campo = $data[$i]; //rescatamos el valor

            //buscamos el dtcampo al que pertenece el valor y lo buscamos
            $dt_campo = DtCampoValor::select(
            'dt_campo_valors.*'
            )
            ->where('dt_campo_valors.dt_id','=', $request['params']['dt'])
            ->where('dt_campo_valors.campo_id','=', $campo['campo_id'])
            ->first();

            if($dt_campo == null)//sino lo encuentra lo creara
            {
               $dt_campo = DtCampoValor::create(
                [
                   'dt_id' => $request['params']['dt'],
                   'campo_id' => $campo['campo_id']
                ]);

                //Hay que encontrar todos los valores anteriores para desactivarlos
                //y crear uno nuevo
                //Recibimos el id del dt
                
                $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo['id'])
                ->update(['activo' => 0]);
                
                //Crea nuevo valor en la tabla de valores
                $newValor = Valor::create([
                    'valor' => $campo['value'],
                    'dt_campo_valor_id' => $dt_campo->id,
                    'user_id' => $request['params']['usuario']
                ]);             
            }
            else
            {
              
                $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo['id'])
                ->update(['activo' => 0]);
                
                //Crea nuevo valor en la tabla de valores
                $newValor = Valor::create([
                    'valor' => $campo['value'],
                    'dt_campo_valor_id' => $dt_campo->id,
                    'user_id' => $request['params']['usuario']
                ]);   
            }
          }
           
          //Recorrido para fotos
          //return $fotos['fotos']['fotos'];
          $campo_foto = $fotos['campo_id'];
          $dt_campo_foto = DtCampoValor::select(
            'dt_campo_valors.*'
            )
            ->where('dt_campo_valors.dt_id','=', $request['params']['dt'])
            ->where('dt_campo_valors.campo_id','=', $campo_foto)
            ->first();

         if($dt_campo_foto == null) //sino encuentra el tipo de campo hay que crearlo
         {
            $dt_campo_foto = DtCampoValor::create(
                [
                   'dt_id' => $request['params']['dt'],
                   'campo_id' => $campo_foto
                ]);

           //RECORREMOS las fotos para insercion
           for ($i=0; $i < count($fotos['fotos']['fotos']) ; $i++) 
           { 
              $foto = $fotos['fotos']['fotos'][$i];
              if($foto['id'] !== 0)
              {
                 $newValor = Valor::create([
                    'valor' => $foto['base64'],
                    'dt_campo_valor_id' => $dt_campo_foto['id'],
                    'user_id' => $request['params']['usuario']
                  ]);
              }
           }
         }
         else
         {
      
            $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo_foto['id'])
                ->update(['activo' => 0]);

            for ($i=0; $i < count($fotos['fotos']['fotos']) ; $i++) 
            { 
               $foto = $fotos['fotos']['fotos'][$i];
               if($foto['id'] !== 0)
               {
                  $newValor = Valor::create([
                    'valor' => $foto['base64'],
                    'dt_campo_valor_id' => $dt_campo_foto['id'],
                    'user_id' => $request['params']['usuario']
                  ]);
               }
            }
         }

      }//sino es guardar
      else
      {
          //Guarda todo y cambia status
          //Se recorren los datos y se extraen los campos, al recorrer el ciclo, se insertaran en la BD
          for ($i=0; $i < count($data) ; $i++) 
          { 
            $campo = $data[$i]; //rescatamos el valor

            $confirmacion_campo = DtCampoValor::select(
            'dt_campo_valors.*'
            )
            ->where('dt_campo_valors.confirmacion_id','=', $request['params']['confirmacion_id'])
            ->where('dt_campo_valors.campo_id','=', $campo['campo_id'])
            ->first();

            if($confirmacion_campo == null)//sino lo encuentra lo creara
            {
               $confirmacion_campo = DtCampoValor::create(
                [
                   'confirmacion_id' => $request['params']['confirmacion_id'],
                   'campo_id' => $campo['campo_id']
                ]);

                //Hay que encontrar todos los valores anteriores para desactivarlos
                //y crear uno nuevo
                
                $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$confirmacion_campo['id'])
                ->update(['activo' => 0]);
                
                //Crea nuevo valor en la tabla de valores
                $newValor = Valor::create([
                    'valor' => $campo['value'],
                    'dt_campo_valor_id' => $confirmacion_campo->id,
                    'user_id' => $request['params']['usuario']
                ]);             
            }
            else
            {
              
                $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$confirmacion_campo['id'])
                ->update(['activo' => 0]);
                
                //Crea nuevo valor en la tabla de valores
                $newValor = Valor::create([
                    'valor' => $campo['value'],
                    'dt_campo_valor_id' => $confirmacion_campo->id,
                    'user_id' => $request['params']['usuario']
                ]);   
            }
          }
           //Actualizamos status de la confirmacion
           
          $cofnirmacionDt = ConfirmacionDt::select('confirmacion_dts.*')->
           where('confirmacion','=',$request['params']['confirmacion'])
           ->first();
          
           date_default_timezone_set('America/Mexico_City');
           $fecha_actual = getdate();
           $hora_actual = ($fecha_actual['hours']-1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
           $newFecha = $fecha_actual['year'].'-'.$fecha_actual['mon'].'-'.$fecha_actual['mday'].' '.$hora_actual; 
 
           //Pasa a documentacion 
           ConfirmacionDt::where('confirmacion','=',$request['params']['confirmacion'])
           ->update([
             'status_id' => 6,
             'updated_at' =>$newFecha,
            ]);

          StatusDt::where('confirmacion_dt_id','=',$cofnirmacionDt['id'])
          ->update([
            'activo' => 0
          ]);


          $newStatus = StatusDt::create([
              'confirmacion_dt_id' => $cofnirmacionDt['id'],
              'status_id' => 6,
              'created_at' => $newFecha,
              'updated_at' =>$newFecha,
            ]);

          
           HorasHistorico::create([
             'hora_id' => 1,
             'status_dts_id' => $newStatus['id'],
             'hora' => $hora_actual
           ]);
           
        }
    }

    public function documentacionValores(Request $request) //pantalla de documentacion
    {
         $valores = $request['params']['valores'];

         for ($i=0; $i < count($valores) ; $i++) 
         { 
           $campo = $valores[$i]; //rescatamos el valor

           $dt_campo = DtCampoValor::select(
           'dt_campo_valors.*'
           )
           ->where('dt_campo_valors.confirmacion_id','=', $request['params']['confirmacion_id'])
           ->where('dt_campo_valors.campo_id','=', $campo['campo_id'])
           ->first();

           if($dt_campo == null)//sino lo encuentra lo creara
           {
              $dt_campo = DtCampoValor::create(
               [
                  'confirmacion_id' => $request['params']['confirmacion_id'],
                  'campo_id' => $campo['campo_id']
               ]);

               //Hay que encontrar todos los valores anteriores para desactivarlos
               //y crear uno nuevo
               
               $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo['id'])
               ->update(['activo' => 0]);
               
               //Crea nuevo valor en la tabla de valores
               $newValor = Valor::create([
                   'valor' => $campo['value'],
                   'dt_campo_valor_id' => $dt_campo->id,
                   'user_id' => $request['params']['usuario']
               ]);             
           }
           else
           {
             
               $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo['id'])
               ->update(['activo' => 0]);
               
               //Crea nuevo valor en la tabla de valores
               $newValor = Valor::create([
                   'valor' => $campo['value'],
                   'dt_campo_valor_id' => $dt_campo->id,
                   'user_id' => $request['params']['usuario']
               ]);   
           }
         }
    }

    public function documentacionFotos (Request $request)
    {
       //RECORRIDO DE PRUEBA
       $fotosNames = $request['fotosNames']; //tenemos el arreglo de fotos
       //Primero guardamos las fotos
       for ($i=0; $i < count($request['fotos']) ; $i++) 
       { 
          $newFotoStorage = $request['fotos'][$i];
          $nombre =  $newFotoStorage->getClientOriginalName();
          $rutaImage = $newFotoStorage->storeAs('img/fotos', $nombre ,'gcs');
          $urlImage = Storage::disk('gcs')->url($rutaImage);
       }

       return json_decode($fotosNames[0]['nombre_foto']);
      
       for ($i=0; $i < count($fotosNames); $i++) 
       { 
          $fotoObject = $fotosNames[$i];

       }

       //$fotoTemp =$fotosNames[0];
       //return $fotoTemp;
       //$nombre =  $fotoTemp->getClientOriginalName();
       //$rutaImage = $fotoTemp->store('img/fotos', $nombre ,'gcs');
       //$urlImage = Storage::disk('gcs')->url($rutaImage);
       //return $urlImage;
       return 'ok fotos';
    }

    public function valoresEnrrampe (Request $request)
    {
      if($request['file'] !== null)
      {
        if(is_file(($request['file'])))
        {
          $file = request('file');
          $nombre_original = $file->getClientOriginalName();
          $ruta_file = $file->storeAs('docs', $nombre_original, 'gcs');
          $urlFile = Storage::disk('gcs')->url($ruta_file);

           $dt_campo = DtCampoValor::select( //buscaremos el valor del archivo o la relacion
            'dt_campo_valors.*'
            )
            ->where('dt_campo_valors.dt_id','=', $request['dt'])
            ->where('dt_campo_valors.campo_id','=', $request['tipo_campo_file'])
            ->first();

            if($dt_campo == null)
            {
                $dt_campo = DtCampoValor::create(
                [
                   'dt_id' => $request['dt'],
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

       //creacion del PDF el cual se debe guardar en confirmacion_dt
       $pdf = App::make('dompdf.wrapper');
       //buscamos el dt
        $dt = Dt::select( 
         'dts.referencia_dt'
         )
         ->where('dts.id','=',$request['dt'])
         ->first();

         //buscamos la confirmacion
         $confirmacion_dt = ConfirmacionDt::select(
            'confirmacion_dts.id',
            'confirmacion_dts.cita'
         )
         ->where('confirmacion_dts.confirmacion','=',$request['confirmacion'])//$request['confirmacion'])
         ->first();
        
         //Consultamos todos loscambios de status por confirmacion
        $statusByConfirmacion = StatusDt::select(
          'status.id as status_id',
          'status.status_padre as status_padre_id',
          'status.nombre as status_name',
          'status.color as color',
          'status_dts.updated_at as status_dt_updated_at'
        )
        ->with([
          'status' => function ($query) 
            {
              return  $query->select(
                'status.*'
              )
              ->with([
                'campos' => function ($query1) 
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
        ->join('dts','dt_campo_valors.dt_id','dts.id')
        ->join('confirmacion_dts', 'confirmacion_dts.dt_id','dts.id')
        ->join('campos','dt_campo_valors.campo_id','campos.id')
        ->join('status','campos.status_id','status.id')
        ->where('valors.activo','=', 1)
        ->where('confirmacion_dts.id','=',$confirmacion_dt['id'])
        ->distinct('valors.id')
        ->get();
     
        //seteamos la data en el pdf para la plantilla
        $data = [
          'confirmacion' =>  $request['confirmacion'],
          'dt' =>  $dt['referencia_dt'],//$dt['referencia_dt'],
          'status_dt' => $statusByConfirmacion,
          'title' =>  $request['confirmacion'].'_'.date('Y-m-d H-m'), // $request['confirmacion'].'_'.now(),
          'cita' =>  $confirmacion_dt['cita'], //$confirmacion_dt['cita']
          'valors' => $valors,
          'firma' => $request['firma']
        ];

        $pdf->loadView('pdfs.plantilla_confirmacion', $data);
                 
        //guardamos en storage
         $ruta_pdf  =  Storage::disk('gcs') //guardamos en google
        ->put(
         'pdfs/'.$request['confirmacion'].'_'.date('Y-m-d').'_'.date('h-i').'.pdf',
          $pdf->output()
        );
       
        $urlPdf = Storage::disk('gcs')->url('pdfs/'.$request['confirmacion'].'_'.date('Y-m-d').'_'.date('h-i').'.pdf');
        //Seteamos el documento en la BD y cambiamos status a liberacion de incidencia
        
        $setPDF = ConfirmacionDt::where('confirmacion','=',$request['confirmacion'])
        ->update([
          'pdf' => $urlPdf,
          'status_id' => 5
        ]);

        //Buscamos el existente
        $cofnirmacionDt = ConfirmacionDt::select('confirmacion_dts.*')
        ->where('confirmacion_dts.confirmacion','=',$request['confirmacion'])
        ->first();

        StatusDt::where('confirmacion_dt_id','=',$cofnirmacionDt['id'])
        ->update([
          'activo' => 0
        ]);

        StatusDt::create([
          'confirmacion_dt_id' => $cofnirmacionDt['id'],
          'status_id' => 5
        ]);
          
        }
        else{
          return 'no es un archivo';
        }
      }    
    }

    public function fotosEnrrampe (Request $request)
    {
       $fotos = $request['params']['fotos']; //tenemos el objeto de fotos dividido por el campo y el objeto de fotos que contiene un array de fotos
       
       for ($i=0; $i < count($fotos['fotos']['fotos']) ; $i++) 
       { 
           $foto = $fotos['fotos']['fotos'][$i]; // tenemos cada objeto de foto
           if($foto['id'] !== 0)
           {
               $dt_campo_foto = DtCampoValor::select(
                   'dt_campo_valors.*'
                   )
                   ->where('dt_campo_valors.dt_id','=', $request['params']['dt'])
                   ->where('dt_campo_valors.campo_id','=', $foto['campo_id'])
                   ->first();

               if($dt_campo_foto !== null)
               {
                   $newValor = Valor::create([
                       'valor' => $foto['base64'],
                       'dt_campo_valor_id' => $dt_campo_foto['id'],
                       'user_id' => $request['params']['usuario']
                     ]);
               }
               else
               {
                   $dt_campo_foto = DtCampoValor::create(
                       [
                          'dt_id' => $request['params']['dt'],
                          'campo_id' => $foto['campo_id']
                       ]);
                   
                  $newValor = Valor::create([
                      'valor' => $foto['base64'],
                      'dt_campo_valor_id' => $dt_campo_foto['id'],
                      'user_id' => $request['params']['usuario']
                    ]);
               }
           }
       }
       
    }

    //Funcion de enrrampado segunda version
    public function valoresEnrrampado (Request $request)
    {
          $data = $request['params']['data'];
          $fotos = $request['params']['fotos'];
          //Guarda todo y cambia status
          //Se recorren los datos y se extraen los campos, al recorrer el ciclo, se insertaran en la BD
          for ($i=0; $i < count($data) ; $i++) 
          { 
            $campo = $data[$i]; //rescatamos el valor

            $dt_campo = DtCampoValor::select(
            'dt_campo_valors.*'
            )
            ->where('dt_campo_valors.confirmacion_id','=', $request['params']['confirmacion_id'])
            ->where('dt_campo_valors.campo_id','=', $campo['campo_id'])
            ->first();

            if($dt_campo == null)//sino lo encuentra lo creara
            {
               $dt_campo = DtCampoValor::create(
                [
                   'confirmacion_id' => $request['params']['confirmacion_id'],
                   'campo_id' => $campo['campo_id']
                ]);

                //Hay que encontrar todos los valores anteriores para desactivarlos
                //y crear uno nuevo
                
                $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo['id'])
                ->update(['activo' => 0]);
                
                //Crea nuevo valor en la tabla de valores
                $newValor = Valor::create([
                    'valor' => $campo['value'],
                    'dt_campo_valor_id' => $dt_campo->id,
                    'user_id' => $request['params']['usuario']
                ]);             
            }
            else
            {
              
                $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo['id'])
                ->update(['activo' => 0]);
                
                //Crea nuevo valor en la tabla de valores
                $newValor = Valor::create([
                    'valor' => $campo['value'],
                    'dt_campo_valor_id' => $dt_campo->id,
                    'user_id' => $request['params']['usuario']
                ]);   
            }
          }
          //Recorrido de fotos
          for ($x=0; $x < count($fotos) ; $x++)
          { 
            $foto = $fotos[$x];
            if($foto['id'] !== 0)
            {
              $dt_campo_foto = DtCampoValor::select(
                'dt_campo_valors.*'
                )
                ->where('dt_campo_valors.confirmacion_id','=', $request['params']['confirmacion_id'])
                ->where('dt_campo_valors.campo_id','=', $foto['campo_id'])
                ->first();

                if($dt_campo_foto !== null)
                {
                    $newValor = Valor::create([
                        'valor' => $foto['base64'],
                        'dt_campo_valor_id' => $dt_campo_foto['id'],
                        'user_id' => $request['params']['usuario']
                      ]);
                }
                else
                {
                    $dt_campo_foto = DtCampoValor::create(
                        [
                           'confirmacion_id' => $request['params']['confirmacion_id'],
                           'campo_id' => $foto['campo_id']
                        ]);
                    
                   $newValor = Valor::create([
                       'valor' => $foto['base64'],
                       'dt_campo_valor_id' => $dt_campo_foto['id'],
                       'user_id' => $request['params']['usuario']
                     ]);
                }
            }
          }
 
           //Cambio al sig status
           $cofnirmacionDt = ConfirmacionDt::select('confirmacion_dts.*')->
           where('confirmacion','=',$request['params']['confirmacion'])
           ->first();

           date_default_timezone_set('America/Mexico_City');
           $fecha_actual = getdate();
           $hora_actual = ($fecha_actual['hours']-1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
           $newFecha = $fecha_actual['year'].'-'.$fecha_actual['mon'].'-'.$fecha_actual['mday'].' '.$hora_actual; 
 
       
           ConfirmacionDt::where('confirmacion','=',$request['params']['confirmacion'])
           ->update([
             'status_id' => 9,
             'updated_at' =>$newFecha,
            ]);
       
          StatusDt::where('confirmacion_dt_id','=',$cofnirmacionDt['id'])
          ->update([
            'activo' => 0
          ]);
       
         $newStatus  = StatusDt::updateOrcreate([
            'confirmacion_dt_id' => $cofnirmacionDt['id'],
            'status_id' => 9,
            'created_at' => $newFecha,
            'updated_at' =>$newFecha,
          ]);
       
          HorasHistorico::updateOrcreate([
            'hora_id' => 7,
            'status_dts_id' => $newStatus['id'],
            'hora' => $hora_actual
          ]);
    }

    public function checkValores (Request $request)
    {
      //return $request;
      //Necesitamos los campos con los valores de este dt_confirmacion con ese status
      $status = Statu::select('status.*')
      ->where('status.id','=',$request['status_id'])
      ->first();

       $campos = Campo::select('campos.id as campo_id','campos.nombre as campo','tipos_campos.nombre as tipo_campo')
       ->join('tipos_campos','campos.tipo_campo_id','tipos_campos.id')
       ->where('campos.status_id','=', $status['id'])
       ->get();

       $valors = Valor::select('valors.*','campos.id as campo_id',)
       ->join('dt_campo_valors','valors.dt_campo_valor_id','dt_campo_valors.id')
       ->join('campos','dt_campo_valors.campo_id','campos.id')
       ->where('campos.status_id','=', $status['id'])
       ->where('valors.activo','=', 1)
       ->where('dt_campo_valors.confirmacion_id','=',  $request['confirmacion_dt_id'])
       ->get();

       /*
       $valors = Valor::select('valors.*','campos.id as campo_id',)
       ->join('dt_campo_valors','valors.dt_campo_valor_id','dt_campo_valors.id')
       ->join('dts','dt_campo_valors.dt_id','dts.id')
       ->join('confirmacion_dts', 'confirmacion_dts.dt_id','dts.id')
       ->join('campos','dt_campo_valors.campo_id','campos.id')
       ->where('campos.status_id','=', $status['status_padre'])
       ->where('valors.activo','=', 1)
       ->where('confirmacion_dts.dt_id','=',  $request['confirmacion_dt_id'])
       ->distinct('valors.id')
       ->get();
       */

       //
       return ['campos' => $campos, 'valors' => $valors ];
    }
}
