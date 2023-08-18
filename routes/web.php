<?php

use App\Http\Controllers\ConfirmacionDtController;
use App\Http\Controllers\OcController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RolesPermissionController;
use App\Http\Controllers\StatusDtController;
use App\Http\Controllers\UserUbicacioneController;
use App\Http\Controllers\ValorController;
use App\Models\StatusDt;
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
//Cambio de status to riesgo
Route::get('/changeToRiesgo', [ConfirmacionDtController::class, 'changeToRiesgo'])->name('changeToRiesgo');
Route::get('/changePorRecibir', [ConfirmacionDtController::class, 'changePorRecibir'])->name('changePorRecibir');
//Cambio de status to espera
Route::get('/changeToEnEspera', [ConfirmacionDtController::class, 'changeToEnEspera'])->name('changeToEnEspera');
Route::get('/changeToEnDocumentacion', [ConfirmacionDtController::class, 'changeToEnDocumentacion'])->name('changeToEnDocumentacion');
//Cambio de status to riesgo
Route::get('/changeToDescarga', [ConfirmacionDtController::class, 'changeToDescarga'])->name('changeToDescarga');
Route::get('/changeEnrrampado', [ConfirmacionDtController::class, 'changeEnrrampado'])->name('changeEnrrampado');
//Ruta para checar historico
Route::get('/showHistorico',[StatusDtController::class, 'showHistorico'])->name('showHistorico');
//Ver valores
Route::get('/checkValores',[ValorController::class, 'checkValores'])->name('checkValores');
//Manage Users view
Route::get('/usuarios',[UserUbicacioneController::class, 'index'])->name('manageUsers.index');
//Edicion de usuario
Route::get('/editUser',[UserUbicacioneController::class, 'update'])->name('editUser');
//Creacion de usuario
Route::get('/saveUser',[UserUbicacioneController::class, 'store'])->name('saveUser');
//Obtener PDF general
Route::get('/getPDF',[ConfirmacionDtController::class, 'getPDF'])->name('getPDF');
//Ruta para enviar correo
Route::get('/sentMail',[ReporteController::class, 'sentMail'])->name('sentMail');
//Apartado de graficas
Route::get('/reporteGraficos',[ReporteController::class, 'reporteGraficos'])->name('reportes.graficos.index');
//Consulta para checar viejes de grafica por click
Route::get('/consultarConfirmaciones',[ConfirmacionDtController::class, 'consultarConfirmaciones'])->name('consultarConfirmaciones');
//Creacion de OCS
Route::get('/saveOcs',[OcController::class, 'store'])->name('saveOcs');
//Consultar OCS
Route::get('/consultarOcs',[OcController::class, 'consultarOcs'])->name('consultarOcs');
//Roles y permisos index
Route::get('/rolesPermisos',[RolesPermissionController::class, 'index'])->name('rolesPermisosIndex');