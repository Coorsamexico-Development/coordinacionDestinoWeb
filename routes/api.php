<?php

use App\Http\Controllers\AutenticatheController;
use App\Http\Controllers\CampoController;
use App\Http\Controllers\ConfirmacionDtController;
use App\Http\Controllers\HorasHistoricoController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\OcController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ValorController;
use App\Models\ConfirmacionDt;
use App\Models\DtCampoValor;
use App\Models\StatusDt;
use App\Models\Valor;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('artisan', function () {
    Artisan::call('migrate', [
        '--force' => true
    ]);
    return "ok";
});

//Autenticacion


Route::middleware('auth:sanctum')->get('/user', function (Request $request)
 {
    return $request->user();
});

//Ruta inicial para obtener DTS
Route::get('/dtsApi',[ConfirmacionDtController::class, 'indexApi'])->name('dtsApi');
//Autenticaciones
Route::post('/sanctum/token', [AutenticatheController::class, 'login']);
//Consultar campos a partir del status padre
Route::get('/camposApi',[CampoController::class, 'indexApi']);
//Consultar campos a partir de status
Route::get('/camposByStatus',[CampoController::class,'camposByStatus']);
//Guardar campos con valores en valores primera pantalla
Route::post('/valoresDeLlegada', [ValorController::class, 'valoresApi']);
//Guardar fotos segunda pantalla
Route::post('/valoresDeDocumentacion',[ValorController::class, 'documentacionValores']);
Route::post('/fotosDocumentacion',[ValorController::class,'documentacionFotos']);
//Ruta de guardado global de enrrampe
Route::post('/valoresEnrrampado',[ValorController::class, 'valoresEnrrampado'])->name('valoresEnrrampado');
//Guardar docs de la espera de ennrampe
Route::post('/fotosEnrrampe',[ValorController::class, 'fotosEnrrampe'])->name('fotosEnrrampe');


Route::get('artisan', function () {
    Artisan::call('migrate', [
        '--force' => true
    ]);
    return "ok";
});

Route::get('/saveFacturados', [OcController::class, 'saveFacturados'])->name('saveFacturados');
//Ruta para consultar productos
Route::get('/indexProductos', [ProductoController::class, 'apiIndex'])->name('indexProductos');
//Ruta para checar el tipo de incidencias
Route::get('/checkIncidencias',[IncidenciaController::class, 'checkIncidencias'])->name('checkIncidencias');
//Save incidencias
Route::post('/saveIncidencias', [IncidenciaController::class, 'saveIncidencias'])->name('saveIncidencias');
Route::get('/checkIncidenciasByOc',[IncidenciaController::class, 'checkIncidenciasByOc'])->name('checkIncidenciasByOc');
//Borrar incidencias con evidencias
Route::get('/eraseIncidenciasWithEvidencias', [IncidenciaController::class, 'eraseIncidenciasWithEvidencias'])->name('eraseIncidenciasWithEvidencias');
//Ruta para guardar ocs cuadradas
Route::post('/saveCuadre',[OcController::class, 'saveCuadre'])->name('saveCuadre');
//Ruta para cambiar y tomar la hr de folios
Route::get('/savehrFolios', [HorasHistoricoController::class, 'savehrFolios'])->name('savehrFolios');
//Ruta para guardar datos y cambiar al status de liberacion
Route::post('/valoresLiberacion',[ConfirmacionDtController::class, 'valoresLiberacion'])->name('valoresLiberacion');
//Ruta para guardar documento de la liberacion
Route::post('/saveDocEnrrampe',[ConfirmacionDtController::class, 'saveDocEnrrampe'])->name('saveDocEnrrampe');
//Valores de firmsas
Route::post('/firmasLiberacion',[ConfirmacionDtController::class, 'firmasLiberacion'])->name('firmasLiberacion');
//Obtener telefono por viaje
Route::get('/getTelephone',[ConfirmacionDtController::class,'getTelephone'])->name('getTelephone');

//Prueba para la estructura del PDF
Route::get('/pdf', function()
{

  $confirmacionAll = ConfirmacionDt::select('confirmacion_dts.*')
  ->where('confirmacion_dts.id','=',1)
  ->first();

  $confirmacionesConMismoDT = ConfirmacionDt::select('confirmacion_dts.*')
  ->where('confirmacion_dts.dt_id','=',$confirmacionAll['dt_id'])
  ->get();

  if(count($confirmacionesConMismoDT) > 0)
  {
    $camposAInsertar = DtCampoValor::select('dt_campo_valors.*',
    'campos.id as campo_id',
    'campos.nombre as campo')
    ->join('campos','dt_campo_valors.campo_id','campos.id')
    ->with('valores')
    ->where([
      ['dt_campo_valors.confirmacion_id', 1],
      ['campos.status_id',4] //a tiempo
    ])
    ->orWhere('campos.status_id','=', 6) //documetar
    ->get();

    //return $camposAInsertar[0]['valores'];

    //Un vez guardados los campos vamos a replicar lo mismo para el historico de status
    $historico_de_status = StatusDt::select('status_dts.*') //son los sattus a replicar para las confirmaciones
    ->where([
      ['status_dts.confirmacion_dt_id',$confirmacionAll['id']],
      ['status_dts.status_id',4] //a tiempo
    ])
    ->orWhere('status_dts.status_id','=', 6) //documetar
    ->get();

    //Falta replicar historico de horas


    for ($i=0; $i < count($confirmacionesConMismoDT) ; $i++) 
    { 
       //Por confirmacion hay que crear el dt campo valor y luego crear el valor y relacionarlo
       $confirmacionActual = $confirmacionesConMismoDT[$i];
       //recorremos los campos
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

       for ($s=0; $s < count($historico_de_status) ; $s++) 
       { 
          $historia_status = $historico_de_status[$s];
          $newHistorica = StatusDt::updateOrCreate([
            'confirmacion_dt_id' => $confirmacionActual['id'],
            'status_id' => $historia_status['status_id']
          ]);
       }
     
    }
  }
   

   /*
    $pdf = App::make('dompdf.wrapper');

    $confirmacion_dt = ConfirmacionDt::select('confirmacion_dts.*')
    ->where('confirmacion_dts.confirmacion','=','22680127')
    ->first();

    $statusByConfirmacion = StatusDt::select(
        'status.id as status_id',
        'status.status_padre as status_padre_id',
        'status.nombre as status_name',
        'status.color as color',
        'status_dts.updated_at as status_dt_updated_at',
        'status_dts.created_at as status_dt_created_at'
      )
      ->with([
        'status' => function ($query) 
          {
            return  $query->select(
              'status.*'
            )
            ->with([
              'campos2' => function ($query1) 
              {
                 return $query1->select(
                  'campos.*',
                  'tipos_campos.nombre as tipo_campo'
                 )
                 ->join('tipos_campos','campos.tipo_campo_id','tipos_campos.id');
              }
            ]);
          }
        ])
      ->join('status','status_dts.status_id','status.id')
      ->where('confirmacion_dt_id','=', $confirmacion_dt['id'])
      ->distinct('status.id')
      ->get();
  
      //Consultamos valores
      $valors = Valor::select('valors.*','campos.id as campo_id','status.id as status_id')
      ->join('dt_campo_valors','valors.dt_campo_valor_id','dt_campo_valors.id')
      ->join('dts','dt_campo_valors.dt_id','dts.id')
      ->join('confirmacion_dts', 'confirmacion_dts.dt_id','dts.id')
      ->join('campos','dt_campo_valors.campo_id','campos.id')
      ->join('status','campos.status_id','status.id')
      ->where('valors.activo','=', 1)
      ->where('confirmacion_dts.id','=',$confirmacion_dt['id'])
      ->distinct('valors.id')
      ->get();
    $data = [
        'confirmacion' =>  '654965',
        'dt' =>  '645312',//$dt['referencia_dt'],
        'status_dt' => $statusByConfirmacion,
        'title' =>  '22680689'.'_'.date('Y-m-d H-m'), // $request['confirmacion'].'_'.now(),
        'cita' =>  $confirmacion_dt['cita'], //$confirmacion_dt['cita']
        'valors' => $valors,
        'firmas' => []
      ];

      $options = new Options();
      $pdf->set_option('isRemoteEnabled', true);
      $pdf->loadView('pdfs.plantilla_confirmacion', $data);
      return $pdf->stream();
      */
});
