<?php

namespace App\Http\Controllers;

use App\Models\DtCampoValor;
use App\Models\Valor;
use Illuminate\Http\Request;

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
              $newValor = Valor::create([
                  'valor' => $foto['photo'],
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
              $newValor = Valor::create([
                  'valor' => $foto['photo'],
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

      }

    }
}
