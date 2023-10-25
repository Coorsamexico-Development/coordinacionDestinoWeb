<?php

namespace App\Http\Controllers;

use App\Models\Evidencia;
use App\Models\Incidencia;
use App\Models\Oc;
use App\Models\Producto;
use App\Models\TipoIncidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncidenciaController extends Controller
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
    public function show(Incidencia $incidencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Incidencia $incidencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incidencia $incidencia)
    {
        //
    }

    public function eraseIncidenciasWithEvidencias (Request $request)
    {
        $incidencia = $request['id'];
        
        $evidencias_de_incidencia = Evidencia::select('evidencias.*')
        ->where('evidencias.incidencia_id','=', $incidencia)
        ->get();

        //Eliminamos las evidencias
        for ($i=0; $i < count($evidencias_de_incidencia) ; $i++) 
        { 
            $evidencia = Evidencia::find($evidencias_de_incidencia[$i]['id']);
            $evidencia->delete();
        }

        $incidenciaAEliminar = Incidencia::find($incidencia);
        $incidenciaAEliminar->delete();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incidencia $incidencia)
    {
        //
    }

    public function checkIncidencias(Request $request)
    {
       return TipoIncidencia::select('tipo_incidencias.*')
       ->where('tipo_incidencias.activo','=',1)
       ->get();
    }

    public function saveIncidencias(Request $request)
    {
       $data = $request['data'];
       //return $data[0]['evidencias'];
       for ($i=0; $i < count($data) ; $i++) 
       { 
         $producto = $data[$i];
         $incidencia = Incidencia::updateOrCreate([
           'ocs_id' => $producto['oc_id'],
           'tipo_incidencia_id' => $producto['tipo_incidencia_id'],
           'cantidad' => $producto['cantidad'],
           'ean_id' => $producto['id']
         ]);

         //Vamos a recorrer las evidencias
         if($producto['tipo_incidencia_id'] == 1 )
         {

         }
         else
         {
            for ($x=0; $x < count($producto['evidencias']) ; $x++) 
            { 
               $evidencia = $producto['evidencias'][$x];
   
               $nombre =  $evidencia->getClientOriginalName();
               $rutaImage = $evidencia->storeAs('img/fotos', $nombre ,'gcs');
               $urlImage = Storage::disk('gcs')->url($rutaImage);
   
               Evidencia::create([
                 'evidencia' => $urlImage,
                 'incidencia_id' => $incidencia['id']
               ]);
            }
         }
       }

       return 'save all ok';
      
    }

    public function saveNewIncidencias(Request $request)
    {
       if(count($request['incidencias']) > 0)
       {
          for ($i=0; $i < count($request['incidencias']) ; $i++) 
          { 

            $incidencia = $request['incidencias'][$i];

            $producto = Producto::select('productos.*')
            ->where('SKU','=',$incidencia['sku'])
            ->first();

            Incidencia::create([
                'ocs_id' => $request['oc_id'],
                'tipo_incidencia_id' => $incidencia['tipo_incidencia_id'],
                'cantidadPOD' => $incidencia['reportePOD'],
                'ean_id' => $producto['id']
            ]);
          }
       }
    }

    public function reportePOD (Request $request)
    {
       Incidencia::where('id','=',$request['incidencia_id'])
       ->update(['cantidadPOD' => $request['valor']]);
    }

    public function borrarIncidencia (Request $request)
    {
       Incidencia::where('id','=',$request['incidencia_id'])
       ->delete();
    }
}
