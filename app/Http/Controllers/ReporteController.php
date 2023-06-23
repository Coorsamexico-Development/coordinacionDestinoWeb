<?php

namespace App\Http\Controllers;

use App\Exports\DtsExport;
use App\Imports\DtsImport;
use App\Models\Cliente;
use App\Models\ConfirmacionDt;
use App\Models\Plataforma;
use App\Models\Statu;
use App\Models\Ubicacione;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Event\Code\Throwable;

class ReporteController extends Controller
{
    //
    public function index()
    {
        $status_padre = Statu::select('status.*')
        ->whereNull('status.status_padre')
        ->get();

        $ubicaciones = Ubicacione::all();

        $plataformas = Plataforma::all();

        //Contadores

        $contadores = Statu::select('status.*')
        ->with(
            'confirmacionesDts' 
            )
        ->whereNotNull('status.status_padre')
        ->get();


        return Inertia::render('Reportes/Reportes.index',[
            'status_padre' => $status_padre,
            'ubicaciones' => $ubicaciones,
            'plataformas' => $plataformas,
            'contadores' => $contadores
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'document' => ['required'],
        ]);

        try  //se intenta subir el doc
        {
          Excel::import(new DtsImport, $request['document']);
          return redirect()->back();
        } 
        catch (\Maatwebsite\Excel\Validators\ValidationException $e) 
        {  
            $failures = $e->failures();
            return redirect()->back()->withErrors([
                'errors' => $failures[0]->errors(),
            ]);
            
        }
    } 

    public function downloadReport() 
    {
        return Excel::download(new DtsExport, 'example.xlsx');
    }
}
