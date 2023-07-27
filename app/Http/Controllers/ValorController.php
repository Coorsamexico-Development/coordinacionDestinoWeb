<?php

namespace App\Http\Controllers;

use App\Models\ConfirmacionDt;
use App\Models\DtCampoValor;
use App\Models\StatusDt;
use App\Models\Valor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

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

    public function valoresApi (Request $request) 
    {

      $data = $request['params']['data'];
      $fotos = $request['params']['fotos'];
      if($request['params']['tipo'] == 'guardar' )
      {
          //Si es guardado envia los datos pero no cambie el status
          //Se recorren los datos y se extraen los campos, al recorrer el ciclo, se insertaran en la BD
          for ($i=0; $i < count($data) ; $i++) 
          { 
            $campo = $data[$i]; //rescatamos el valor

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

           //Actualizamos status de la confirmacion
          $cofnirmacionDt = ConfirmacionDt::select('confirmacion_dts.*')->
           where('confirmacion','=',$request['params']['confirmacion'])
           ->first();

           ConfirmacionDt::where('confirmacion','=',$request['params']['confirmacion'])
           ->update([
             'status_id' => 8
            ]);

          StatusDt::where('confirmacion_dt_id','=',$cofnirmacionDt['id'])
          ->update([
            'activo' => 0
          ]);

          StatusDt::create([
            'confirmacion_dt_id' => $cofnirmacionDt['id'],
            'status_id' => 8
          ]);
        }
    }

    public function documentacionValores(Request $request)
    {
        $fotos = $request['params']['fotos']; //tenemos el arreglo de fotos
        if($request['params']['tipo'] == 'guardar')
        {
            for ($i=0; $i < count($fotos) ; $i++) 
            { 
                $foto = $fotos[$i]; // tenemos cada objeto de foto
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
        else
        {
            for ($i=0; $i < count($fotos) ; $i++) 
            { 
                $foto = $fotos[$i]; // tenemos cada objeto de foto
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

            //Cambio al sig status
              $cofnirmacionDt = ConfirmacionDt::select('confirmacion_dts.*')->
              where('confirmacion','=',$request['params']['confirmacion'])
              ->first();

              ConfirmacionDt::where('confirmacion','=',$request['params']['confirmacion'])
              ->update([
                'status_id' => 10
               ]);

             StatusDt::where('confirmacion_dt_id','=',$cofnirmacionDt['id'])
             ->update([
               'activo' => 0
             ]);

             StatusDt::create([
               'confirmacion_dt_id' => $cofnirmacionDt['id'],
               'status_id' => 10
             ]);

        }
    }

    public function valoresEnrrampe (Request $request)
    {
      if($request['file'] !== null)
      {
        if(is_file(($request['file'])))
        {
          $file = request('file');
          $nombre_original = $file->getClientOriginalName();
          $ruta_icono = $file->storeAs('docs', $nombre_original, 'gcs');
          $urlIcono = Storage::disk('gcs')->url($ruta_icono);

          return 'ok';
        }
        else{
          return 'no es';
        }
      }
    }

    public function fotosEnrrampe (Request $request)
    {
      $fotos = $request['params']['fotos']; //tenemos el arreglo de fotos
      $jsonPrueba = [];
      for ($i=0; $i < count($fotos['fotos']) ; $i++) 
      { 
        $foto = $fotos['fotos'][$i];
        array_push($jsonPrueba, $foto);
      }

      return $jsonPrueba;
    }

    public function checkValores (Request $request)
    {
      //Necesitamos los campos con los valores de este dt_confirmacion con ese status
      $confirmacion_dt= ConfirmacionDt::select('confirmacion_dts.*')
        ->where('confirmacion_dts.id', '=', $request['confirmacion_dt_id'])
        ->where('confirmacion_dts.status_id','=',$request['status_id'])
        ->first();

      return $valores = Valor::select('valors.*', 'campos.nombre as campo', 'tipos_campos.nombre as tipo_campo')
       ->join('dt_campo_valors','valors.dt_campo_valor_id','dt_campo_valors.id')
       ->join('dts','dt_campo_valors.dt_id','dts.id')
       ->join('confirmacion_dts', 'confirmacion_dts.dt_id','dts.id')
       ->join('campos','dt_campo_valors.campo_id','campos.id')
       ->join('tipos_campos','campos.tipo_campo_id','tipos_campos.id')
       ->where('confirmacion_dts.dt_id','=',  $request['confirmacion_dt_id'])
       ->where('confirmacion_dts.status_id','=', $request['status_id'])
       ->where('valors.activo','=', 1)
       ->get();
    }
}
