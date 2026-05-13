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
    //return $data[0]['evidencias'];
    for ($i = 0; $i < count($data); $i++) {
      $producto = $data[$i];
      $incidencia = Incidencia::updateOrCreate([
        'ocs_id' => $producto['oc_id'],
        'tipo_incidencia_id' => $producto['tipo_incidencia_id'],
        'cantidad' => $producto['cantidad'],
        'producto_id' => $producto['id']
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
          'producto_id' => $producto['id']
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
    //tomamos la incidencia que se eliminara
    $incidencia = Incidencia::select('incidencias.*')
      ->where('id', '=', $request['incidencia_id'])
      ->first();

    if (!$incidencia) return;

    $confirmacion_dt_id = null;

    if ($incidencia->ocs_id) {
        //Buscamos la oc para tomar la confirmacion
        $oc = Oc::find($incidencia->ocs_id);
        if ($oc) $confirmacion_dt_id = $oc->confirmacion_dt_id;
    } elseif ($incidencia->factura_id) {
        //Buscamos la factura para tomar la confirmacion
        $factura = Factura::find($incidencia->factura_id);
        if ($factura) $confirmacion_dt_id = $factura->confirmacion_dt_id;
    }

    //borramos la incidencia
    $incidencia->delete();

    if (!$confirmacion_dt_id) return;

    //Hay que checar si el viaje tiene otras incidencias para marcarlo con liberacion al 100
    //y generar otro status_dt del historico
    $ocs = Oc::select('ocs.*')
      ->with('incidencias')
      ->where('confirmacion_dt_id', '=', $confirmacion_dt_id)
      ->get();

    $facturas = Factura::select('facturas.*')
      ->with('incidencias')
      ->where('confirmacion_dt_id', '=', $confirmacion_dt_id)
      ->get();

    $hayIncidencias = [];

    foreach ($ocs as $oc) {
      foreach ($oc->incidencias as $inc) {
        array_push($hayIncidencias, $inc);
      }
    }

    foreach ($facturas as $factura) {
      foreach ($factura->incidencias as $inc) {
        array_push($hayIncidencias, $inc);
      }
    }

    $historico_status = StatusDt::select('status_dts.*')
      ->where('status_dts.confirmacion_dt_id', '=', $confirmacion_dt_id)
      ->where(function ($query) {
        $query->where('status_dts.status_id', '=', 10)
          ->orWhere('status_dts.status_id', '=', 11);
      })
      ->orderBy('status_dts.id', 'DESC') //ordenamos por el ultimo status
      ->first();

    if (count($hayIncidencias) == 0) {
      $confirmacion = ConfirmacionDt::find($confirmacion_dt_id);
      if ($historico_status && ($historico_status['status_id'] == 11 || $confirmacion['status_id'] == 11)) //si esta al 100 se cambiara de status
      {
        $confirmacion->update(['status_id' => 10]);

        StatusDt::where('status_dts.confirmacion_dt_id', '=', $confirmacion_dt_id)
          ->update(['activo' => 0]);

        StatusDt::create([
          'confirmacion_dt_id' => $confirmacion_dt_id,
          'status_id' => 10
        ]);
      }
    }
  }

  public function getIncidenciasByOc(Request $request)
  {
    return Incidencia::withDetails()
      ->where('ocs_id', '=', $request['oc_id'])
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

      $producto = Producto::where('SKU', '=', $incidencia['sku'])->first();

      if ($producto) {
          Incidencia::create([
            'ocs_id' => $request['oc'],
            'producto_id' => $producto['id'],
            'cantidad' => $incidencia['cantidad'],
            'tipo_incidencia_id' => $incidencia['tipo_incidencia_id']
          ]);
      }
    }

    $this->actualizarStatusLiberacion($request['confirmacion']);
  }

  public function saveIncidenciasByFactura(Request $request)
  {
    for ($i = 0; $i < count($request['incidencias']); $i++) {
      $incidencia = $request['incidencias'][$i];

      $producto = Producto::where('SKU', '=', $incidencia['sku'])->first();

      if ($producto) {
          Incidencia::create([
            'factura_id' => $request['factura_id'],
            'producto_id' => $producto['id'],
            'cantidad' => $incidencia['cantidad'],
            'tipo_incidencia_id' => $incidencia['tipo_incidencia_id']
          ]);
      }
    }

    $this->actualizarStatusLiberacion($request['confirmacion']);
  }

  private function actualizarStatusLiberacion($confirmacion_dt_id)
  {
    $ocs = Oc::with('incidencias')->where('confirmacion_dt_id', $confirmacion_dt_id)->get();
    $facturas = Factura::with('incidencias')->where('confirmacion_dt_id', $confirmacion_dt_id)->get();

    $hayIncidencias = false;
    foreach ($ocs as $oc) {
        if ($oc->incidencias->count() > 0) { $hayIncidencias = true; break; }
    }
    if (!$hayIncidencias) {
        foreach ($facturas as $factura) {
            if ($factura->incidencias->count() > 0) { $hayIncidencias = true; break; }
        }
    }

    $confirmacion = ConfirmacionDt::find($confirmacion_dt_id);
    if (!$confirmacion) return;

    $historico_status = StatusDt::where('confirmacion_dt_id', $confirmacion_dt_id)
      ->whereIn('status_id', [10, 11])
      ->orderBy('id', 'DESC')
      ->first();

    if ($hayIncidencias) {
      if (!$historico_status || $historico_status->status_id == 10 || $confirmacion->status_id == 10) {
        $confirmacion->update(['status_id' => 11]);
        StatusDt::where('confirmacion_dt_id', $confirmacion_dt_id)->update(['activo' => 0]);
        StatusDt::create(['confirmacion_dt_id' => $confirmacion_dt_id, 'status_id' => 11]);
      }
    }
  }
}
