<?php

use App\Http\Controllers\ConfirmacionDtController;
use App\Http\Controllers\ReporteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () 
    {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

//Ruta de carga para pantalla de reportes
Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
//Ruta para cargar dts
Route::post('/reportes',[ReporteController::class, 'store'])->name('reportes.store');
//Ruta para descargar ejemplo de reporte
Route::get('/downloadReport',[ReporteController::class, 'downloadReport'])->name('downloadReport');
//Ruta pra consultar confirmaciones/dts por ubicacion y cliente
Route::get('/getConfirmaciones',[ConfirmacionDtController::class, 'index'])->name('getConfirmacions');


