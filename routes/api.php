<?php

use App\Http\Controllers\AutenticatheController;
use App\Http\Controllers\CampoController;
use App\Http\Controllers\ConfirmacionDtController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\OcController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ValorController;
use Illuminate\Http\Request;
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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
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
//Guardar valores 3era pantalla
Route::post('/valoresEnrrampe',[ValorController::class, 'valoresEnrrampe'])->name('valoresEnrrampe');
Route::post('/fotosEnrrampe',[ValorController::class, 'fotosEnrrampe'])->name('fotosEnrrampe');
//Ruta de guardado global de enrrampe
Route::post('/valoresEnrrampado',[ValorController::class, 'valoresEnrrampado'])->name('valoresEnrrampado');

Route::get('artisan', function () {
    Artisan::call('migrate', [
        '--force' => true
    ]);
    return "ok";
});

Route::get('/saveFacturados', [OcController::class, 'saveFacturados'])->name('saveFacturados');
//Ruta para consultar productos
Route::get('/indexProductos', [ProductoController::class, 'apiIndex'])->name('indexProductos');
//Ruta para checar las incidencias
Route::get('/checkIncidencias',[IncidenciaController::class, 'checkIncidencias'])->name('checkIncidencias');
//Save incidencias
Route::get('saveIncidencias', [IncidenciaController::class, 'saveIncidencias'])->name('saveIncidencias')
//Prueba para la estructura del PDF
/*
Route::get('/pdf', function(){
    return view('pdfs.plantilla_confirmacion')->render();
});
*/