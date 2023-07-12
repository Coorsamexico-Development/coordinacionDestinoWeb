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
        $campos = $request['data'];
        $fotos = $request['fotos'];
      if($request['tipo'] == 'guardar' )
      {
          //Si es guardado envia los datos pero no cambie el status
          //Se recorren los datos y se extraen los campos, al recorrer el ciclo, se insertaran en la BD
          return count($campos);
          for ($i=0; $i < count($campos) ; $i++)
          { 
             $campo_valor = $campos[$i];
             $dt_campo_exist = DtCampoValor::select(
                'dt_campo_valors.*'
             )->where('dt_id','=', $request['dt'])
             ->where('campo_id','=', $campo_valor->campo_id)
             ->first();
             return $dt_campo_exist;
          }
      }

      if($request['tipo'] == 'siguiente' )
      {
        //Guarda todo y cambia status

      }

    }
}
