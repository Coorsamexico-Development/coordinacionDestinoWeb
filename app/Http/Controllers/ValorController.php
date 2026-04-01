<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Events\NewNotification;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

  public function valoresApi(Request $request)  //pantalla de llegada registra hr de llegada
  {
    $confirmacionDt = ConfirmacionDt::select('confirmacion_dts.*')->where('confirmacion_dts.confirmacion', '=', $request['params']['confirmacion'])
        ->where('confirmacion_dts.id', '=', $request['params']['confirmacion_id'])
        ->first();

    if ($confirmacionDt->status_id >= StatusEnum::DOCUMENTADO->value) {  
      return response()->json([
        'message' => 'La confirmacion ya se encuentra en status de documentado o superior'
      ]);
    }



    $data = $request['params']['data'];
    $tipo = $request['params']['tipo'] ?? null;
    $usuarioId = $request['params']['usuario'] ?? null;

    // Se recorren los datos y se extraen los campos para insertarlos en la BD
    foreach ($data as $campo) {
      $condicionesBusqueda = $tipo == 'guardar'
        ? ['dt_id' => $request['params']['dt'], 'campo_id' => $campo['campo_id']]
        : ['confirmacion_id' => $request['params']['confirmacion_id'], 'campo_id' => $campo['campo_id']];

      $dt_campo = DtCampoValor::firstOrCreate($condicionesBusqueda);

      // Desactivamos valores anteriores
      Valor::where('dt_campo_valor_id', $dt_campo->id)->update(['activo' => 0]);

      // Crea nuevo valor
      Valor::create([
        'valor' => $campo['value'],
        'dt_campo_valor_id' => $dt_campo->id,
        'user_id' => $usuarioId
      ]);
    }

    if ($tipo == 'guardar') {
      // Recorrido para fotos
      if (isset($fotos['campo_id'])) {
        $campo_foto = $fotos['campo_id'];
        $dt_campo_foto = DtCampoValor::firstOrCreate([
          'dt_id' => $request['params']['dt'],
          'campo_id' => $campo_foto
        ]);

        // Desactivar fotos anteriores
        Valor::where('dt_campo_valor_id', $dt_campo_foto->id)->update(['activo' => 0]);

        // Insertar nuevas fotos
        if (isset($fotos['fotos']['fotos'])) {
          foreach ($fotos['fotos']['fotos'] as $foto) {
            if ($foto['id'] !== 0) {
              Valor::create([
                'valor' => $foto['base64'],
                'dt_campo_valor_id' => $dt_campo_foto->id,
                'user_id' => $usuarioId
              ]);
            }
          }
        }
      }
    } else {
      // Actualizamos status de la confirmacion
      // Al hacer el guardado de llegada comprobaremos si alguna otra confirmacion tiene 
      // el mismo dt en dado caso de eso se copiara la misma informacion de valores desde a tiempo
      
      StatusDt::where('confirmacion_dt_id', $confirmacionDt['id'])->update(['activo' => 0]);
      
      // Cambiamos status del viaje
      ConfirmacionDt::where('id', $confirmacionDt['id'])->update(['status_id' => 6]);

      StatusDt::updateOrCreate([
        'confirmacion_dt_id' => $confirmacionDt['id'],
        'status_id' => 6
      ]);
      
      broadcast(new NewNotification($confirmacionDt))->toOthers();
    }

    return response()->json([
      'message' => 'Valores guardados correctamente',
    ]);
  }

  public function documentacionValores(Request $request) //pantalla de documentacion
  {
    $request->validate([
      'params.confirmacion_id' => 'required',
      'params.valores' => 'required',
    ]);

    $confirmacionDt = ConfirmacionDt::where('id', $request['params']['confirmacion_id'])
      ->first();
    if ($confirmacionDt->status_id >= StatusEnum::EN_ESPERA_DE_RAMPA->value) {  
      return response()->json([
        'message' => 'La confirmacion ya se encuentra en status de documentado o superior'
      ]);
    }

    $valores = $request['params']['valores'];
    $confirmacionId = $request['params']['confirmacion_id'];
    $usuarioId = Auth::user()->id;


    try {
      DB::beginTransaction();
      foreach ($valores as $campo) {
        $dt_campo = DtCampoValor::firstOrCreate([
          'confirmacion_id' => $confirmacionId,
          'campo_id' => $campo['campo_id']
        ]);

        //Desactivar valores anteriores
        Valor::where('dt_campo_valor_id', $dt_campo->id)
          ->update(['activo' => 0]);

        //Crea nuevo valor
        Valor::create([
          'valor' => $campo['value'],
          'dt_campo_valor_id' => $dt_campo->id,
          'user_id' => $usuarioId
        ]);
      }

      DB::commit();
      return response()->json(['message' => 'Valores guardados correctamente']);
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json([
        'message' => 'Error al guardar valores',
      ], 500);
    }
  }

  public function documentacionFotos(Request $request) //guarda y cambia a status 7 (en eespera de rampa)
  {
    Log::info("llega a documentacion fotos");
    $request->validate([
      'dt' => 'required|exists:dts,id',
      'fotos' => 'array',
      'fotosNames' => 'array',
      'confirmacion_id' => 'required',
    ]);
    $confirmacionDt = ConfirmacionDt::where('id', $request['confirmacion_id'])
      ->where('dt_id', $request['dt'])
      ->first();
    if ($confirmacionDt->status_id >= StatusEnum::EN_ESPERA_DE_RAMPA->value) {  
      return response()->json([
        'message' => 'La confirmacion ya se encuentra en status de documentado o superior'
      ]);
    }


    $usuarioId = Auth::user()->id;
    // RECORRIDO DE PRUEBA
    $fotosNames = $request['fotosNames']; //tenemos el arreglo de fotos
    
    // Primero guardamos las fotos
    foreach ($request['fotos'] as $newFotoStorage) {
      $nombre =  $newFotoStorage->getClientOriginalName();
      $rutaImage = $newFotoStorage->storeAs('img/fotos', $nombre, 'gcs');
    }

    foreach ($fotosNames as $fotoObject) {
      $url = Storage::disk('gcs')->url('img/fotos/' . $fotoObject['nombre_foto']);

      $dt_campo_foto = DtCampoValor::firstOrCreate([
        'confirmacion_id' => $request['confirmacion_id'],
        'campo_id' => $fotoObject['campo_id']
      ]);

      Valor::create([
        'valor' => $url,
        'dt_campo_valor_id' => $dt_campo_foto->id,
        'user_id' => $usuarioId
      ]);
    }

    // Cambiaremos de status
    

    date_default_timezone_set('America/Mexico_City');
    $fecha_actual = getdate();
    $hora_actual = ($fecha_actual['hours'] - 1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
    $newFecha = $fecha_actual['year'] . '-' . $fecha_actual['mon'] . '-' . $fecha_actual['mday'] . ' ' . $hora_actual;

    $confirmacionesConMismoDT = ConfirmacionDt::where('dt_id', $confirmacionDt->dt_id)->get();

    // Copiado de informacion a otras confirmaciones
    if ($confirmacionesConMismoDT->count() > 1) {
      $camposAInsertar = DtCampoValor::join('campos', 'dt_campo_valors.campo_id', 'campos.id')
        ->with('valores')
        ->where('dt_campo_valors.confirmacion_id', $confirmacionDt->id)
        ->whereIn('campos.status_id', [4, 6])
        ->select('dt_campo_valors.*', 'campos.id as campo_id', 'campos.nombre as campo')
        ->get();

      $historico_de_status = StatusDt::where('confirmacion_dt_id', $confirmacionDt->id)
        ->whereIn('status_id', [4, 6])
        ->get();

      foreach ($confirmacionesConMismoDT as $confirmacionActual) {
        if ($confirmacionActual->id !== $confirmacionDt->id) {
          // Si hay campos para insertar de esta confirmación
          foreach ($camposAInsertar as $campoActual) {
            $newDtCampoValor = DtCampoValor::updateOrCreate([
              'campo_id' => $campoActual->campo_id,
              'confirmacion_id' => $confirmacionActual->id
            ]);

            foreach ($campoActual->valores as $valorActual) {
              Valor::updateOrCreate([
                'valor' => $valorActual->valor,
                'dt_campo_valor_id' => $newDtCampoValor->id,
                'user_id' => $valorActual->user_id
              ]);
            }
          }

          // Replicar historico_de_status a las demás
          foreach ($historico_de_status as $historia_status) {
            StatusDt::updateOrCreate([
              'confirmacion_dt_id' => $confirmacionActual->id,
              'status_id' => $historia_status->status_id
            ]);
          }
        }
      }
    }

    // Actualizamos Status General en un único bucle correcto para sí misma y las copiadas
    foreach ($confirmacionesConMismoDT as $confirmacionActual) {
      ConfirmacionDt::where('id', $confirmacionActual->id)
        ->update([
          'status_id' => 7,
          'updated_at' => $newFecha,
        ]);

      StatusDt::where('confirmacion_dt_id', $confirmacionActual->id)
        ->update([
          'activo' => 0
        ]);

      $newStatus = StatusDt::create([
        'confirmacion_dt_id' => $confirmacionActual->id,
        'status_id' => 7,
        'created_at' => $newFecha,
        'updated_at' => $newFecha,
      ]);

      HorasHistorico::create([
        'hora_id' => 2,
        'status_dts_id' => $newStatus->id,
        'hora' => $hora_actual
      ]);
    }

    broadcast(new NewNotification($confirmacionDt))->toOthers();
    return 'ok fotos';
  }

  //Funcion de enrrampado segunda version
  public function valoresEnrrampado(Request $request)
  {
    $valores = $request['params']['valores'];

    foreach ($valores as $campo) {
      $dt_campo = DtCampoValor::firstOrCreate([
        'confirmacion_id' => $request['params']['confirmacion_id'],
        'campo_id' => $campo['campo_id']
      ]);

      // Desactivamos valores anteriores
      Valor::where('dt_campo_valor_id', $dt_campo->id)->update(['activo' => 0]);

      // Crea nuevo valor en la tabla de valores
      Valor::create([
        'valor' => $campo['value'],
        'dt_campo_valor_id' => $dt_campo->id,
        'user_id' => $request['params']['usuario']
      ]);
    }
  }

  public function fotosEnrrampe(Request $request)
  {
    $request->validate([
      'fotos' => ['required', 'array'],
      'fotos.*' => ['required', 'file', 'mimes:jpeg,jpg,png,gif,svg,webp', 'max:2048'],
      'fotosNames' => ['required', 'array'],
      'fotosNames.*.campo_id' => ['required', 'integer'],
      'fotosNames.*.nombre_foto' => ['required', 'string'],
      'confirmacion_id' => ['required', 'integer'],
      'dt' => ['required', 'integer', 'exists:dts,id'],
      'confirmacion' => ['required'],
    ]);
    
    $confirmacionDt = ConfirmacionDt::findOrFail($request['confirmacion_id']);
    
    if ($confirmacionDt->status_id >= StatusEnum::DESENRAMPADO->value) {
      return response()->json([
        'success' => true,
        'message' => 'La confirmacion ya se encuentra en status de desenrampado o superior'
      ]);
    }
    
    $fotosNames = $request['fotosNames']; //tenemos el arreglo de fotos
    
    try {
      DB::beginTransaction();
      //Primero guardamos las fotos
      foreach ($request['fotos'] as $foto) {
        $nombre =  $foto->getClientOriginalName();
        $rutaImage = $foto->storeAs('img/fotos', $nombre, 'gcs');
        $urlImage = Storage::disk('gcs')->url($rutaImage);
      }

      foreach ($fotosNames as $fotoObject) {
        // $fotoObject = json_decode($fotoObject, true);
        $url = Storage::disk('gcs')->url('img/fotos/' . $fotoObject['nombre_foto']);
        $dt_campo_foto = DtCampoValor::firstOrCreate(
          [
            'confirmacion_id' => $request['confirmacion_id'],
            'campo_id' => $fotoObject['campo_id']
          ]
        );

        Valor::updateOrCreate([
          'valor' => $url,
          'dt_campo_valor_id' => $dt_campo_foto['id'],
          'user_id' => $request['usuario']
        ]);
      }


      date_default_timezone_set('America/Mexico_City');
      $fecha_actual = getdate();
      $hora_actual = ($fecha_actual['hours'] - 1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
      $newFecha = $fecha_actual['year'] . '-' . $fecha_actual['mon'] . '-' . $fecha_actual['mday'] . ' ' . $hora_actual;


      $confirmacionDt->update([
          'status_id' => 9,
          'updated_at' => $newFecha,
        ]);

      StatusDt::where('confirmacion_dt_id', '=', $confirmacionDt['id'])
        ->update([
          'activo' => 0
        ]);

      $newStatus  = StatusDt::updateOrcreate([
        'confirmacion_dt_id' => $confirmacionDt['id'],
        'status_id' => 9,
        'created_at' => $newFecha,
        'updated_at' => $newFecha,
      ]);

      HorasHistorico::updateOrcreate([
        'hora_id' => 5,
        'status_dts_id' => $newStatus['id'],
        'hora' => $hora_actual
      ]);
      DB::commit();

      broadcast(new NewNotification($confirmacionDt))->toOthers();

      return response()->json([
        'success' => true,
        'message' => 'Registros actualizados correctamente'
      ], 200);
    } catch (Exception $e) {
      DB::rollBack();
      Log::error($e);
      return response()->json([
        'success' => false,
        'message' => 'Error al actualizar los registros',
        'error' => $e->getMessage()
      ], 500);
    }
  }


  public function checkValores(Request $request)
  {
    //return $request;
    //Necesitamos los campos con los valores de este dt_confirmacion con ese status
    $status = Statu::select('status.*')
      ->where('status.id', '=', $request['status_id'])
      ->first();

    $campos = Campo::select('campos.id as campo_id', 'campos.nombre as campo', 'tipos_campos.nombre as tipo_campo')
      ->join('tipos_campos', 'campos.tipo_campo_id', 'tipos_campos.id')
      ->where('campos.status_id', '=', $status['id'])
      ->get();

    $valors = Valor::select('valors.*', 'campos.id as campo_id',)
      ->join('dt_campo_valors', 'valors.dt_campo_valor_id', 'dt_campo_valors.id')
      ->join('campos', 'dt_campo_valors.campo_id', 'campos.id')
      ->where('campos.status_id', '=', $status['id'])
      ->where('valors.activo', '=', 1)
      ->where('dt_campo_valors.confirmacion_id', '=',  $request['confirmacion_dt_id'])
      ->get();

    $evidencias = Valor::select('valors.*', 'campos.id as campo_id',)
      ->join('dt_campo_valors', 'valors.dt_campo_valor_id', 'dt_campo_valors.id')
      ->join('campos', 'dt_campo_valors.campo_id', 'campos.id')
      ->where('campos.status_id', '=', $status['id'])
      ->where('valors.is_evidencia', '=', 1)
      ->where('dt_campo_valors.confirmacion_id', '=',  $request['confirmacion_dt_id'])
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
    return response()->json([
      'campos' => $campos,
      'valors' => $valors,
      'evidencias' => $evidencias
    ], 200);
  }
}
