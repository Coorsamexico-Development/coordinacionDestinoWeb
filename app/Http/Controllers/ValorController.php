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
      $data = $request['data'];
      $fotos = $request['fotos'];

      if($request['tipo'] == 'guardar' )
      {
          //Si es guardado envia los datos pero no cambie el status
          //Se recorren los datos y se extraen los campos, al recorrer el ciclo, se insertaran en la BD
          /*
          for ($i=0; $i < count($data) ; $i++) 
          { 
            $campo = $data[$i]; //rescatamos el valor

            $dt_campo = DtCampoValor::select(
            'dt_campo_valors.*'
            )
            ->where('dt_campo_valors.dt_id','=', $request['dt'])
            ->where('dt_campo_valors.campo_id','=', $campo['campo_id'])
            ->first();

            if($dt_campo == null)//sino lo encuentra lo creara
            {
               $dt_campo = DtCampoValor::create(
                [
                   'dt_id' => $request['dt'],
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
                    'user_id' => $request['usuario']
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
                    'user_id' => $request['usuario']
                ]);   
            }
          }
           */
          //Recorrido para fotos
          //return $fotos['fotos']['fotos'];
          $campo_foto = $fotos['campo_id'];
          $dt_campo_foto = DtCampoValor::select(
            'dt_campo_valors.*'
            )
            ->where('dt_campo_valors.dt_id','=', $request['dt'])
            ->where('dt_campo_valors.campo_id','=', $campo_foto)
            ->first();

         if($dt_campo_foto == null) //sino encuentra el tipo de campo hay que crearlo
         {
            $dt_campo_foto = DtCampoValor::create(
                [
                   'dt_id' => $request['dt'],
                   'campo_id' => $campo_foto
                ]);

           //RECORREMOS las fotos para insercion
           for ($i=0; $i < count($fotos['fotos']['fotos']) ; $i++) 
           { 
              $foto = $fotos['fotos']['fotos'][$i];
              $base64 = base64_encode(file_get_contents($foto['base64']));
              
           }
         }
         else
         {
            for ($i=0; $i < count($fotos['fotos']['fotos']) ; $i++) 
            { 
               $foto = $fotos['fotos']['fotos'][$i];
               $image_64 = base64_decode($foto['base64']);
               $filename_path = md5(time().uniqid()).".jpg";
               
               $path = "uploads/".$filename_path.".png";
               //$file= file_put_contents($path,$image_64);
               
               $url= Storage::disk('gcs')->put($filename_path, $image_64);
              
               $newValor = Valor::create([
                'valor' => $url,
                'dt_campo_valor_id' => $dt_campo_foto->id,
                'user_id' => $request['usuario']
            ]);
            }
         }


      }

      //evidencias bd catalogos, 

      if($request['tipo'] == 'siguiente' )
      {
        //Guarda todo y cambia status
          //Si es guardado envia los datos pero no cambie el status
          //Se recorren los datos y se extraen los campos, al recorrer el ciclo, se insertaran en la BD
          for ($i=0; $i < count($data) ; $i++) 
          { 
            $campo = $data[$i]; //rescatamos el valor

            $dt_campo = DtCampoValor::select(
            'dt_campo_valors.*'
            )
            ->where('dt_campo_valors.dt_id','=', $request['dt'])
            ->where('dt_campo_valors.campo_id','=', $campo['campo_id'])
            ->first();

            if($dt_campo == null)//sino lo encuentra lo creara
            {
               $dt_campo = DtCampoValor::create(
                [
                   'dt_id' => $request['dt'],
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
                    'user_id' => $request['usuario']
                ]);
                   
            }
          }

          //Recorrido para fotos
          //return $fotos['fotos']['fotos'];
          $campo_foto = $fotos['campo_id'];
          $dt_campo_foto = DtCampoValor::select(
            'dt_campo_valors.*'
            )
            ->where('dt_campo_valors.dt_id','=', $request['dt'])
            ->where('dt_campo_valors.campo_id','=', $campo_foto)
            ->first();
         
         if($dt_campo_foto == null)
         {
            $dt_campo_foto = DtCampoValor::create(
                [
                   'dt_id' => $request['dt'],
                   'campo_id' => $campo_foto
                ]);

           //RECORREMOS las fotos para insercion
           for ($x=0; $x < count($fotos['fotos']['fotos']) ; $x++) 
           { 
              $foto = $fotos['fotos']['fotos'][$x];
              $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo_foto['id'])
              ->update(['activo' => 0]);
              //Crea nuevo valor en la tabla de valores
               //Guardar en storage de Google
               $folderPath = "evidencias/";
               $base64Image = explode(";base64,", $foto['file']);
               $explodeImage = explode("image/", $base64Image[0]);
               $imageName = $explodeImage[1];
               $image_base64 = base64_decode($base64Image[1]);
               $file = $folderPath . uniqid() . '. '.$imageName; 
               $urlFoto= null;
               try {
                $s3Url = $folderPath . $imageName;
                $urlFoto= Storage::disk('gcs')->put($s3Url, 'file' , 'gcs');
               } catch (Exception $e) {
                Log::error($e);
                }

              $newValor = Valor::create([
                  'valor' => $urlFoto,
                  'dt_campo_valor_id' => $dt_campo_foto->id,
                  'user_id' => $request['usuario']
              ]);
           }
         }
         else
         {
           //RECORREMOS las fotos para insercion
           for ($x=0; $x < count($fotos['fotos']['fotos']) ; $x++) 
           { 
              $foto = $fotos['fotos']['fotos'][$x];
              $valorADesactivar = Valor::where('valors.dt_campo_valor_id','=',$dt_campo_foto['id'])
              ->update(['activo' => 0]);
              //Crea nuevo valor en la tabla de valores
                //Guardar en storage de Google

           }
         }
      }
    }
}
