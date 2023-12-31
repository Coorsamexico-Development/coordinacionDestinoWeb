<?php

namespace App\Http\Controllers;

use App\Exports\OcsExport;
use App\Imports\OcsImport;
use App\Models\ConfirmacionDt;
use App\Models\Oc;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
            'ocs' => 'required',
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

        //return $confirmacion['id'];

        if($request->has('producto_id'))
        {
            return Oc::select('ocs.*')
            ->with(['incidencias'  => function ($query) use ($request) 
            {
                $query->select(
                    'incidencias.*',
                    'tipo_incidencias.nombre as tipo_incidencia',
                    'productos.SKU as sku'
                  )
                  ->with('evidencias')
                  ->join('tipo_incidencias','incidencias.tipo_incidencia_id','tipo_incidencias.id')
                  ->join('productos','incidencias.ean_id','productos.id')
                  ->where('incidencias.ean_id','=',$request['producto_id'])
                  ->get();
                 }
                ])
            ->where('ocs.confirmacion_dt_id','=', $confirmacion['id'])
            ->get();
        }
        else
        {
            return Oc::select('ocs.*')
            ->with(['incidencias'  => function ($query) {
                $query->select(
                    'incidencias.*',
                    'tipo_incidencias.nombre as tipo_incidencia',
                    'productos.SKU as sku'
                  )
                  ->with('evidencias')
                  ->join('tipo_incidencias','incidencias.tipo_incidencia_id','tipo_incidencias.id')
                  ->join('productos','incidencias.ean_id','productos.id')
                  ->get();
                 }
                ])
            ->where('ocs.confirmacion_dt_id','=', $confirmacion['id'])
            ->get();
        }

    }

    public function getOcsApi (Request $request)
    {
        return ConfirmacionDt::select(
            'confirmacion_dts.*'
        )->where('confirmacion_dts.id','=', $request['id'])
        ->with('ocs')
        ->get();
    }

    public function saveFacturados (Request $request)
    {
       $ocs = $request['ocs'];
       for ($i=0; $i < count($ocs) ; $i++)
        { 
           $oc = $ocs[$i];
           Oc::where('id','=',$oc['oc_id'])
           ->update([
              'facturado' => $oc['value']
           ]);
        }
    } 

    public function saveCuadre (Request $request)
    {
        $ocsCuadradas =  $request['params']['ocs'];
        if(count($ocsCuadradas) !== 0)
        {
          for ($i=0; $i < count($ocsCuadradas) ; $i++) 
          { 
             $oc = $ocsCuadradas[$i];
             if($oc['pod'] !== null)
             {
                Oc::where('id','=', $oc['id'])
                ->update([
                   'enPOD' => $oc['pod']
                ]);
             }
             else
             {
                Oc::where('id','=', $oc['id'])
                ->update([
                   'enPOD' => $oc['enPOD']
                ]);
             }   
          }
        }
    }


    public function ocsByViaje (Request $request) 
    {
          return Oc::select('ocs.*')
          ->with(['incidencias'  => function ($query) {
              $query->select(
                  'incidencias.*',
                  'tipo_incidencias.nombre as tipo_incidencia',
                  'productos.descripcion as producto',
                  'productos.SKU as sku'
                )
                ->with('evidencias')
                ->join('tipo_incidencias','incidencias.tipo_incidencia_id','tipo_incidencias.id')
                ->join('productos','incidencias.ean_id','productos.id')
                ->orderBy('incidencias.id','ASC')
                ->get();
               }
              ])
          ->where('ocs.confirmacion_dt_id','=',$request['confirmacion_dt_id'])
          ->get();
    }

    public function saveFacturas(Request $request)
    {
       switch ($request['tipo']) 
       {
         case 'FACMI':
              Oc::where('id','=',$request['oc_id'])
              ->update([
                'FACMI' => $request['valor']
              ]);
            break;
         case 'FACSAD':
               Oc::where('id','=',$request['oc_id'])
               ->update([
                 'FACSAD' => $request['valor']
               ]);
             break;
         case 'folio_recibidor':
              Oc::where('id','=',$request['oc_id'])
              ->update([
                'folio_recibidor' => $request['valor']
              ]);
            break;
       }

       return 'ok';
    }

    public function getOcsExample (Request $request)
    {
      return Excel::download(new OcsExport, 'ejemplo_ocs.xlsx');
    }

    public function newOcsExcel (Request $request)
    {

       Excel::import(new OcsImport($request['confirmacion']), $request['document']);

    }
}
