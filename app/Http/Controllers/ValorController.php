<?php

namespace App\Http\Controllers;

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

    $data = $request['params']['data'];
    if ($request['params']['tipo'] == 'guardar') {
      //Si es guardado envia los datos pero no cambie el status
      //Se recorren los datos y se extraen los campos, al recorrer el ciclo, se insertaran en la BD
      for ($i = 0; $i < count($data); $i++) {
        $campo = $data[$i]; //rescatamos el valor

        //buscamos el dtcampo al que pertenece el valor y lo buscamos
        $dt_campo = DtCampoValor::select(
          'dt_campo_valors.*'
        )
          ->where('dt_campo_valors.dt_id', '=', $request['params']['dt'])
          ->where('dt_campo_valors.campo_id', '=', $campo['campo_id'])
          ->first();

        if ($dt_campo == null) //sino lo encuentra lo creara
        {
          $dt_campo = DtCampoValor::create(
            [
              'dt_id' => $request['params']['dt'],
              'campo_id' => $campo['campo_id']
            ]
          );

          //Hay que encontrar todos los valores anteriores para desactivarlos
          //y crear uno nuevo
          //Recibimos el id del dt

          $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $dt_campo['id'])
            ->update(['activo' => 0]);

          //Crea nuevo valor en la tabla de valores
          $newValor = Valor::create([
            'valor' => $campo['value'],
            'dt_campo_valor_id' => $dt_campo->id,
            'user_id' => $request['params']['usuario']
          ]);
        } else {

          $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $dt_campo['id'])
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
        ->where('dt_campo_valors.dt_id', '=', $request['params']['dt'])
        ->where('dt_campo_valors.campo_id', '=', $campo_foto)
        ->first();

      if ($dt_campo_foto == null) //sino encuentra el tipo de campo hay que crearlo
      {
        $dt_campo_foto = DtCampoValor::create(
          [
            'dt_id' => $request['params']['dt'],
            'campo_id' => $campo_foto
          ]
        );

        //RECORREMOS las fotos para insercion
        for ($i = 0; $i < count($fotos['fotos']['fotos']); $i++) {
          $foto = $fotos['fotos']['fotos'][$i];
          if ($foto['id'] !== 0) {
            $newValor = Valor::create([
              'valor' => $foto['base64'],
              'dt_campo_valor_id' => $dt_campo_foto['id'],
              'user_id' => $request['params']['usuario']
            ]);
          }
        }
      } else {

        $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $dt_campo_foto['id'])
          ->update(['activo' => 0]);

        for ($i = 0; $i < count($fotos['fotos']['fotos']); $i++) {
          $foto = $fotos['fotos']['fotos'][$i];
          if ($foto['id'] !== 0) {
            $newValor = Valor::create([
              'valor' => $foto['base64'],
              'dt_campo_valor_id' => $dt_campo_foto['id'],
              'user_id' => $request['params']['usuario']
            ]);
          }
        }
      }
    } //sino es guardar
    else {
      //Guarda todo y cambia status
      //Se recorren los datos y se extraen los campos, al recorrer el ciclo, se insertaran en la BD
      for ($i = 0; $i < count($data); $i++) {
        $campo = $data[$i]; //rescatamos el valor

        $confirmacion_campo = DtCampoValor::select(
          'dt_campo_valors.*'
        )
          ->where('dt_campo_valors.confirmacion_id', '=', $request['params']['confirmacion_id'])
          ->where('dt_campo_valors.campo_id', '=', $campo['campo_id'])
          ->first();

        if ($confirmacion_campo == null) //sino lo encuentra lo creara
        {
          $confirmacion_campo = DtCampoValor::create(
            [
              'confirmacion_id' => $request['params']['confirmacion_id'],
              'campo_id' => $campo['campo_id']
            ]
          );

          //Hay que encontrar todos los valores anteriores para desactivarlos
          //y crear uno nuevo

          $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $confirmacion_campo['id'])
            ->update(['activo' => 0]);

          //Crea nuevo valor en la tabla de valores
          $newValor = Valor::create([
            'valor' => $campo['value'],
            'dt_campo_valor_id' => $confirmacion_campo->id,
            'user_id' => $request['params']['usuario']
          ]);
        } else {

          $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $confirmacion_campo['id'])
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
      //Al hacer el guardado de llegada comprobaremos si alguna otra confirmacion tiene 
      //el mismo dt en dado caso de eso se copiara la misma informacion de valores desde a tiempo
      $cofnirmacionDt = ConfirmacionDt::select('confirmacion_dts.*')->where('confirmacion_dts.confirmacion', '=', $request['params']['confirmacion'])
        ->where('confirmacion_dts.dt_id', '=', $request['params']['dt'])
        ->first();
      /*
       $confirmacionesConMismoDT = ConfirmacionDt::select('confirmacion_dts.*')
       ->where('confirmacion_dts.dt_id','=',$cofnirmacionDt['dt_id'])
       ->get();
           
       if(count($confirmacionesConMismoDT) > 1 )
       {
         $camposAInsertar = DtCampoValor::select(
          'dt_campo_valors.*',
          'campos.id as campo_id',
          'campos.nombre as campo')
           ->join('campos','dt_campo_valors.campo_id','campos.id')
           ->with('valores')
           ->where('dt_campo_valors.confirmacion_id','=',$cofnirmacionDt['id'])
           ->where(function($query) 
           {
             $query->where('campos.status_id','=',4)
               ->orWhere('campos.status_id','=',6);
          })
          ->get();

          $historico_de_status = StatusDt::select('status_dts.*') //son los sattus a replicar para las confirmaciones
          ->where(
            'status_dts.confirmacion_dt_id','=',$cofnirmacionDt['id'])
          ->where(function($query)
           {
             $query->where('status_dts.status_id','=',4)
             ->orWhere('status_dts.status_id','=',6);
           })
          ->get();

          for ($i=0; $i < count($confirmacionesConMismoDT) ; $i++) 
          {
            //Por confirmacion hay que crear el dt campo valor y luego crear el valor y relacionarlo
            $confirmacionActual = $confirmacionesConMismoDT[$i];
            //Hay que guardar los campo_valor
            if(count($camposAInsertar) > 0)
            {
              for ($x=0; $x < count($camposAInsertar) ; $x++) 
              { 
                $campoActual = $camposAInsertar[$x];
                //creamos o sustituimos el dt campo a crear con la confirmacion y el campo actual
                $newDtCampoValor = DtCampoValor::updateOrCreate([
                  'campo_id' => $campoActual['campo_id'],
                  'confirmacion_id' => $confirmacionActual['id']
                ]);
            
                  //Una vez creado el dt creamos el valor igual pero lo asignamos a ese dt campo valor
                  for ($t=0; $t < count($camposAInsertar[$x]['valores']) ; $t++) 
                  { 
                     $valorActual = $camposAInsertar[$x]['valores'][$t];
                     $newValor = Valor::updateOrCreate([
                        'valor' => $valorActual['valor'],
                        'dt_campo_valor_id' => $newDtCampoValor['id'],
                        'user_id' => $valorActual['user_id']
                     ]);
                  }
              }
            }
            
            if(count($historico_de_status)> 0)
            {
            //Un vez guardados los campos vamos a replicar lo mismo para el historico de status
            for ($s=0; $s < count($historico_de_status) ; $s++) 
            { 
               $historia_status = $historico_de_status[$s];
               $newHistorica = StatusDt::updateOrCreate([
                 'confirmacion_dt_id' => $confirmacionActual['id'],
                 'status_id' => $historia_status['status_id']
               ]);
            }
            }

           date_default_timezone_set('America/Mexico_City');
           $fecha_actual = getdate();
           $hora_actual = ($fecha_actual['hours']-1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
           $newFecha = $fecha_actual['year'].'-'.$fecha_actual['mon'].'-'.$fecha_actual['mday'].' '.$hora_actual; 
            //Cambiamos status del viaje
            ConfirmacionDt::where('confirmacion_dts.id','=',$confirmacionActual['id'])
            ->update([
              'status_id' => 6,
              'updated_at' =>$newFecha,
            ]);

           StatusDt::where('confirmacion_dt_id','=',$confirmacionActual['id'])
           ->update([
             'activo' => 0
           ]);

           $newStatus = StatusDt::create([
              'confirmacion_dt_id' => $confirmacionActual['id'],
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
       else
       {
           StatusDt::where('confirmacion_dt_id','=',$cofnirmacionDt['id'])
           ->update([
             'activo' => 0
           ]);
           //Cambiamos status del viaje
           ConfirmacionDt::where('confirmacion_dts.id','=',$cofnirmacionDt['id'])
           ->update([
             'status_id' => 6
           ]);

           StatusDt::updateOrCreate([
               'confirmacion_dt_id' => $cofnirmacionDt['id'],
               'status_id' => 6
           ]);
       }
       */
      StatusDt::where('confirmacion_dt_id', '=', $cofnirmacionDt['id'])
        ->update([
          'activo' => 0
        ]);
      //Cambiamos status del viaje
      ConfirmacionDt::where('confirmacion_dts.id', '=', $cofnirmacionDt['id'])
        ->update([
          'status_id' => 6
        ]);

      StatusDt::updateOrCreate([
        'confirmacion_dt_id' => $cofnirmacionDt['id'],
        'status_id' => 6
      ]);
      broadcast(new NewNotification($cofnirmacionDt))->toOthers();
    }
  }

  public function documentacionValores(Request $request) //pantalla de documentacion
  {
    $valores = $request['params']['valores'];

    for ($i = 0; $i < count($valores); $i++) {
      $campo = $valores[$i]; //rescatamos el valor

      $dt_campo = DtCampoValor::select(
        'dt_campo_valors.*'
      )
        ->where('dt_campo_valors.confirmacion_id', '=', $request['params']['confirmacion_id'])
        ->where('dt_campo_valors.campo_id', '=', $campo['campo_id'])
        ->first();

      if ($dt_campo == null) //sino lo encuentra lo creara
      {
        $dt_campo = DtCampoValor::create(
          [
            'confirmacion_id' => $request['params']['confirmacion_id'],
            'campo_id' => $campo['campo_id']
          ]
        );

        //Hay que encontrar todos los valores anteriores para desactivarlos
        //y crear uno nuevo

        $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $dt_campo['id'])
          ->update(['activo' => 0]);

        //Crea nuevo valor en la tabla de valores
        $newValor = Valor::create([
          'valor' => $campo['value'],
          'dt_campo_valor_id' => $dt_campo->id,
          'user_id' => $request['params']['usuario']
        ]);
      } else {

        $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $dt_campo['id'])
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

  public function documentacionFotos(Request $request) //guarda y cambia a status 7 (en eespera de rampa)
  {
    //RECORRIDO DE PRUEBA
    $fotosNames = $request['fotosNames']; //tenemos el arreglo de fotos
    //Primero guardamos las fotos
    for ($i = 0; $i < count($request['fotos']); $i++) {
      $newFotoStorage = $request['fotos'][$i];
      $nombre =  $newFotoStorage->getClientOriginalName();
      $rutaImage = $newFotoStorage->storeAs('img/fotos', $nombre, 'gcs');
      $urlImage = Storage::disk('gcs')->url($rutaImage);
    }

    $array = [];
    for ($x = 0; $x < count($fotosNames); $x++) {
      $fotoObject = $fotosNames[$x];
      $url = Storage::disk('gcs')->url('img/fotos/' . $fotoObject['nombre_foto']);

      $dt_campo_foto = DtCampoValor::select(
        'dt_campo_valors.*'
      )
        ->where('dt_campo_valors.confirmacion_id', '=', $request['confirmacion_id'])
        ->where('dt_campo_valors.campo_id', '=', $fotoObject['campo_id'])
        ->first();

      if ($dt_campo_foto !== null) {
        $newValor = Valor::create([
          'valor' => $url,
          'dt_campo_valor_id' => $dt_campo_foto['id'],
          'user_id' => $request['usuario']
        ]);
      } else {
        $dt_campo_foto = DtCampoValor::create(
          [
            'confirmacion_id' => $request['confirmacion_id'],
            'campo_id' => $fotoObject['campo_id']
          ]
        );

        $newValor = Valor::create([
          'valor' => $url,
          'dt_campo_valor_id' => $dt_campo_foto['id'],
          'user_id' => $request['usuario']
        ]);
      }
    }

    //cambiaremos de status
    $cofnirmacionDt = ConfirmacionDt::select('confirmacion_dts.*')
      ->where('confirmacion', '=', $request['confirmacion'])
      ->where('dt_id', '=', $request['dt'])
      ->first();

    date_default_timezone_set('America/Mexico_City');
    $fecha_actual = getdate();
    $hora_actual = ($fecha_actual['hours'] - 1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
    $newFecha = $fecha_actual['year'] . '-' . $fecha_actual['mon'] . '-' . $fecha_actual['mday'] . ' ' . $hora_actual;

    ConfirmacionDt::where('confirmacion', '=', $request['confirmacion'])
      ->update([
        'status_id' => 7,
        'updated_at' => $newFecha,
      ]);

    StatusDt::where('confirmacion_dt_id', '=', $cofnirmacionDt['id'])
      ->update([
        'activo' => 0
      ]);

    $newStatus  = StatusDt::create([
      'confirmacion_dt_id' => $cofnirmacionDt['id'],
      'status_id' => 7,
      'created_at' => $newFecha,
      'updated_at' => $newFecha,
    ]);

    HorasHistorico::create([
      'hora_id' => 2,
      'status_dts_id' => $newStatus['id'],
      'hora' => $hora_actual
    ]);


    //Cambiamos status del viaje
    ConfirmacionDt::where('confirmacion_dts.id', '=', $cofnirmacionDt['id'])
      ->update([
        'status_id' => 7
      ]);

    StatusDt::updateOrCreate([
      'confirmacion_dt_id' => $cofnirmacionDt['id'],
      'status_id' => 7
    ]);

    //Copiado de informacion
    $confirmacionesConMismoDT = ConfirmacionDt::select('confirmacion_dts.*')
      ->where('confirmacion_dts.dt_id', '=', $cofnirmacionDt['dt_id'])
      ->get();

    if (count($confirmacionesConMismoDT) > 1) {
      $camposAInsertar = DtCampoValor::select(
        'dt_campo_valors.*',
        'campos.id as campo_id',
        'campos.nombre as campo'
      )
        ->join('campos', 'dt_campo_valors.campo_id', 'campos.id')
        ->with('valores')
        ->where('dt_campo_valors.confirmacion_id', '=', $cofnirmacionDt['id'])
        ->where(function ($query) {
          $query->where('campos.status_id', '=', 4)
            ->orWhere('campos.status_id', '=', 6);
        })
        ->get();

      $historico_de_status = StatusDt::select('status_dts.*') //son los sattus a replicar para las confirmaciones
        ->where(
          'status_dts.confirmacion_dt_id',
          '=',
          $cofnirmacionDt['id']
        )
        ->where(function ($query) {
          $query->where('status_dts.status_id', '=', 4)
            ->orWhere('status_dts.status_id', '=', 6);
        })
        ->get();

      for ($i = 0; $i < count($confirmacionesConMismoDT); $i++) {
        //Por confirmacion hay que crear el dt campo valor y luego crear el valor y relacionarlo
        $confirmacionActual = $confirmacionesConMismoDT[$i];
        //Hay que guardar los campo_valor
        if (count($camposAInsertar) > 0) {
          for ($x = 0; $x < count($camposAInsertar); $x++) {
            $campoActual = $camposAInsertar[$x];
            //creamos o sustituimos el dt campo a crear con la confirmacion y el campo actual
            $newDtCampoValor = DtCampoValor::updateOrCreate([
              'campo_id' => $campoActual['campo_id'],
              'confirmacion_id' => $confirmacionActual['id']
            ]);

            //Una vez creado el dt creamos el valor igual pero lo asignamos a ese dt campo valor
            for ($t = 0; $t < count($camposAInsertar[$x]['valores']); $t++) {
              $valorActual = $camposAInsertar[$x]['valores'][$t];
              $newValor = Valor::updateOrCreate([
                'valor' => $valorActual['valor'],
                'dt_campo_valor_id' => $newDtCampoValor['id'],
                'user_id' => $valorActual['user_id']
              ]);
            }
          }
        }

        if (count($historico_de_status) > 0) {
          //Un vez guardados los campos vamos a replicar lo mismo para el historico de status
          for ($s = 0; $s < count($historico_de_status); $s++) {
            $historia_status = $historico_de_status[$s];
            $newHistorica = StatusDt::updateOrCreate([
              'confirmacion_dt_id' => $confirmacionActual['id'],
              'status_id' => $historia_status['status_id']
            ]);
          }
        }

        date_default_timezone_set('America/Mexico_City');
        $fecha_actual = getdate();
        $hora_actual = ($fecha_actual['hours'] - 1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
        $newFecha = $fecha_actual['year'] . '-' . $fecha_actual['mon'] . '-' . $fecha_actual['mday'] . ' ' . $hora_actual;
        //Cambiamos status del viaje
        ConfirmacionDt::where('confirmacion_dts.id', '=', $confirmacionActual['id'])
          ->update([
            'status_id' => 7,
            'updated_at' => $newFecha,
          ]);

        StatusDt::where('confirmacion_dt_id', '=', $confirmacionActual['id'])
          ->update([
            'activo' => 0
          ]);

        $newStatus = StatusDt::create([
          'confirmacion_dt_id' => $confirmacionActual['id'],
          'status_id' => 7,
          'created_at' => $newFecha,
          'updated_at' => $newFecha,
        ]);


        HorasHistorico::create([
          'hora_id' => 2,
          'status_dts_id' => $newStatus['id'],
          'hora' => $hora_actual
        ]);
      }
    } else {
      StatusDt::where('confirmacion_dt_id', '=', $cofnirmacionDt['id'])
        ->update([
          'activo' => 0
        ]);
      //Cambiamos status del viaje
      ConfirmacionDt::where('confirmacion_dts.id', '=', $cofnirmacionDt['id'])
        ->update([
          'status_id' => 7
        ]);

      StatusDt::updateOrCreate([
        'confirmacion_dt_id' => $cofnirmacionDt['id'],
        'status_id' => 7
      ]);
    }

    broadcast(new NewNotification($cofnirmacionDt))->toOthers();
    return 'ok fotos';
  }

  //Funcion de enrrampado segunda version
  public function valoresEnrrampado(Request $request)
  {
    $valores = $request['params']['valores'];

    for ($i = 0; $i < count($valores); $i++) {
      $campo = $valores[$i]; //rescatamos el valor

      $dt_campo = DtCampoValor::select(
        'dt_campo_valors.*'
      )
        ->where('dt_campo_valors.confirmacion_id', '=', $request['params']['confirmacion_id'])
        ->where('dt_campo_valors.campo_id', '=', $campo['campo_id'])
        ->first();

      if ($dt_campo == null) //sino lo encuentra lo creara
      {
        $dt_campo = DtCampoValor::create(
          [
            'confirmacion_id' => $request['params']['confirmacion_id'],
            'campo_id' => $campo['campo_id']
          ]
        );

        //Hay que encontrar todos los valores anteriores para desactivarlos
        //y crear uno nuevo

        $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $dt_campo['id'])
          ->update(['activo' => 0]);

        //Crea nuevo valor en la tabla de valores
        $newValor = Valor::create([
          'valor' => $campo['value'],
          'dt_campo_valor_id' => $dt_campo->id,
          'user_id' => $request['params']['usuario']
        ]);
      } else {

        $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $dt_campo['id'])
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

  public function fotosEnrrampe(Request $request)
  {
    $request->validate([
      'fotos' => ['required', 'array'],
      'fotos.*' => ['required', 'file', 'mimes:jpeg,jpg,png,gif,svg,webp', 'max:2048'],
      'fotosNames' => ['required', 'array'],
      'fotosNames.*.campo_id' => ['required', 'integer'],
      'fotosNames.*.nombre_foto' => ['required', 'string'],
      'confirmacion_id' => ['required', 'integer', 'exists:confirmacion_dts,id'],
      'dt' => ['required', 'integer', 'exists:dts,id'],
      'confirmacion' => ['required'],
    ]);
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

      //Cambio al sig status
      $cofnirmacionDt = ConfirmacionDt::select('confirmacion_dts.*')
        ->where('confirmacion_dts.confirmacion', '=', $request['confirmacion'])
        ->where('confirmacion_dts.dt_id', '=', $request['dt'])
        ->firstOrFail();

      date_default_timezone_set('America/Mexico_City');
      $fecha_actual = getdate();
      $hora_actual = ($fecha_actual['hours'] - 1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
      $newFecha = $fecha_actual['year'] . '-' . $fecha_actual['mon'] . '-' . $fecha_actual['mday'] . ' ' . $hora_actual;


      ConfirmacionDt::where('confirmacion', '=', $request['confirmacion'])
        ->update([
          'status_id' => 9,
          'updated_at' => $newFecha,
        ]);

      StatusDt::where('confirmacion_dt_id', '=', $cofnirmacionDt['id'])
        ->update([
          'activo' => 0
        ]);

      $newStatus  = StatusDt::updateOrcreate([
        'confirmacion_dt_id' => $cofnirmacionDt['id'],
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

      broadcast(new NewNotification($cofnirmacionDt))->toOthers();

      return response()->json([
        'success' => true,
        'message' => 'Registros actualizados correctamente'
      ], 200);
    } catch (\Exception $e) {
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
