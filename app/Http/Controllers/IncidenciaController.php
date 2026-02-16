<?php

namespace App\Http\Controllers;

use App\Exports\IncidenciasExport;
use App\Models\ConfirmacionDt;
use App\Models\Evidencia;
use App\Models\Incidencia;
use App\Models\Oc;
use App\Models\Producto;
use App\Models\StatusDt;
use App\Models\TipoIncidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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

  public function eraseIncidenciasWithEvidencias(Request $request)
  {
    $incidencia = $request['id'];

    $evidencias_de_incidencia = Evidencia::select('evidencias.*')
      ->where('evidencias.incidencia_id', '=', $incidencia)
      ->get();

    //Eliminamos las evidencias
    for ($i = 0; $i < count($evidencias_de_incidencia); $i++) {
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
      ->where('tipo_incidencias.activo', '=', 1)
      ->get();
  }

  public function saveIncidencias(Request $request)
  {
    $data = $request['data'];
    Log::info($data);
    //return $data[0]['evidencias'];
    for ($i = 0; $i < count($data); $i++) {
      $producto = $data[$i];
      $incidencia = Incidencia::updateOrCreate([
        'ocs_id' => $producto['oc_id'],
        'tipo_incidencia_id' => $producto['tipo_incidencia_id'],
        'cantidad' => $producto['cantidad'],
        'ean_id' => $producto['id']
      ]);

      //Vamos a recorrer las evidencias
      if ($producto['tipo_incidencia_id'] == 1) {
      } else {
        for ($x = 0; $x < count($producto['evidencias']); $x++) {
          $evidencia = $producto['evidencias'][$x];

          $nombre =  $evidencia->getClientOriginalName();
          $rutaImage = $evidencia->storeAs('img/fotos', $nombre, 'gcs');
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
    if (count($request['incidencias']) > 0) {
      for ($i = 0; $i < count($request['incidencias']); $i++) {

        $incidencia = $request['incidencias'][$i];

        $producto = Producto::select('productos.*')
          ->where('SKU', '=', $incidencia['sku'])
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

  public function reportePOD(Request $request)
  {
    Incidencia::where('id', '=', $request['incidencia_id'])
      ->update(['cantidadPOD' => $request['valor']]);
  }

  public function borrarIncidencia(Request $request)
  {
    //tomamos la evidencia que se eliminara para buscar la oc y luego la confirmacion
    $incidencia = Incidencia::select('incidencias.*')
      ->where('id', '=', $request['incidencia_id'])
      ->first();

    //Buscamos la oc para tomar la confirmacion
    $oc = Oc::select('ocs.*')
      ->where('id', '=', $incidencia['ocs_id'])
      ->first();

    $confirmacion = ConfirmacionDt::select('confirmacion_dts.*')
      ->where('id', '=', $oc['confirmacion_dt_id'])
      ->first();

    //borramos la incidencia
    Incidencia::where('id', '=', $request['incidencia_id'])
      ->delete();
    //Hay que checar si el viaje tiene otras incidencias para marcarlo con liberacion al 100
    //y generar otro status_dt del historico
    $ocs = Oc::select('ocs.*')
      ->with('incidencias')
      ->where('confirmacion_dt_id', '=', $confirmacion['id'])
      ->get();

    $hayIncidencias = [];

    for ($x = 0; $x < count($ocs); $x++) {
      $oc = $ocs[$x];
      for ($s = 0; $s < count($oc['incidencias']); $s++) {
        $checkIncidencia = $oc['incidencias'][$s];
        array_push($hayIncidencias, $checkIncidencia);
      }
    }

    $historico_status = StatusDt::select('status_dts.*')
      ->where('status_dts.confirmacion_dt_id', '=', $confirmacion['id'])
      ->where(function ($query) {
        $query->where('status_dts.status_id', '=', 10)
          ->orWhere('status_dts.status_id', '=', 11);
      })
      ->orderBy('status_dts.id', 'DESC') //ordenamos por el ultimo status
      ->first();

    if (count($hayIncidencias) == 0) {
      if ($historico_status['status_id'] == 11 || $confirmacion['status_id'] == 11) //si esta al 100 se cambiara de status
      {
        ConfirmacionDt::where('confirmacion_dts.id', '=', $confirmacion['id'])
          ->update([
            'status_id' => 10
          ]);

        StatusDt::where('status_dts.confirmacion_dt_id', '=', $confirmacion['id'])
          ->update([
            'activo' => 0
          ]);

        StatusDt::create([
          'confirmacion_dt_id' => $confirmacion['id'],
          'status_id' => 10
        ]);
      }
    }
  }

  public function getIncidenciasByOc(Request $request)
  {
    return Incidencia::select(
      'incidencias.*',
      'tipo_incidencias.nombre as tipo_incidencia',
      'productos.descripcion as producto',
      'productos.SKU as sku'
    )
      ->with('evidencias')
      ->join('tipo_incidencias', 'incidencias.tipo_incidencia_id', 'tipo_incidencias.id')
      ->join('productos', 'incidencias.ean_id', 'productos.id')
      ->where('ocs_id', '=', $request['oc_id'])
      ->orderBy('incidencias.id', 'ASC')
      ->get();
  }

  public function dowloadIncidenciasByOc(Request $request)
  {
    return Excel::download(new IncidenciasExport($request['viaje']), 'Reporte_Incidencias.xlsx');
  }

  public function saveIncidenciasByOc(Request $request)
  {

    for ($i = 0; $i < count($request['incidencias']); $i++) {
      $incidencia = $request['incidencias'][$i];

      $producto = Producto::select('productos.*')
        ->where('productos.SKU', '=', $incidencia['sku'])
        ->first();

      Incidencia::create([
        'ocs_id' => $request['oc'],
        'ean_id' => $producto['id'],
        'cantidad' => $incidencia['cantidad'],
        'tipo_incidencia_id' => $incidencia['tipo_incidencia_id']
      ]);
    }


    //Hay que checar si el viaje tiene incidencias para marcarlo con liberacion con incidencia
    //y generar otro status_dt del historico
    $ocs = Oc::select('ocs.*')
      ->with('incidencias')
      ->where('confirmacion_dt_id', '=', $request['confirmacion'])
      ->get();

    $hayIncidencias = [];

    for ($x = 0; $x < count($ocs); $x++) {
      $oc = $ocs[$x];
      for ($s = 0; $s < count($oc['incidencias']); $s++) {
        $checkIncidencia = $oc['incidencias'][$s];
        array_push($hayIncidencias, $checkIncidencia);
      }
    }

    $historico_status = StatusDt::select('status_dts.*')
      ->where('status_dts.confirmacion_dt_id', '=', $request['confirmacion'])
      ->where(function ($query) {
        $query->where('status_dts.status_id', '=', 10)
          ->orWhere('status_dts.status_id', '=', 11);
      })
      ->orderBy('status_dts.id', 'DESC') //ordenamos por el ultimo status
      ->first();

    //si hay incidencias checamos el historico para ver si esta liberado con incidencia, sino
    //hay que asignarlo
    $confirmacion = ConfirmacionDt::select('confirmacion_dts.*')
      ->where('confirmacion_dts.id', '=', $request['confirmacion'])
      ->first();

    if (count($hayIncidencias) > 0) {
      if ($historico_status['status_id'] == 10 || $confirmacion['status_id'] == 10) //si esta al 100 se cambiara de status
      {
        ConfirmacionDt::where('confirmacion_dts.id', '=', $request['confirmacion'])
          ->update([
            'status_id' => 11
          ]);

        StatusDt::where('status_dts.confirmacion_dt_id', '=', $request['confirmacion'])
          ->update([
            'activo' => 0
          ]);

        StatusDt::create([
          'confirmacion_dt_id' => $request['confirmacion'],
          'status_id' => 11
        ]);
      }
    }
  }
}
