<?php

namespace App\Http\Controllers;

use App\Exports\DtsExport;
use App\Imports\DtsImport;
use App\Mail\PDFMail;
use App\Models\Cliente;
use App\Models\ConfirmacionDt;
use App\Models\Plataforma;
use App\Models\Role;
use App\Models\Statu;
use App\Models\StatusDt;
use App\Models\Ubicacione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Event\Code\Throwable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ReporteController extends Controller
{
    public function index()
    {  
       $status_padre = Statu::select('status.*')
        ->with(['status_hijos' => function ($query)
        {
            $query->select('status.*')
            ->whereNotNull('status.status_padre')
            ->groupBy('status.id');
        }])
        ->whereNull('status.status_padre')
        ->get();

         //confirmacion_dts.*,
         //count(confirmacion_dts.id) as contador,
        $ubicaciones = Ubicacione::select('ubicaciones.*')
       ->with('confirmacionesDts')
       ->get();


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


    public function sentMail (Request $request)
    {
        /*
          Credenciales de correo
          reportes.coordinacion@outlook.com
          c00rs4m3x1c0
        */ 

      //Buscamos la confirmacion
      $file = Storage::disk('gcs')->get('pdfs/'.$request['pdf']);

      if (count($request['emails']) > 0) 
      {
        $asunto = $request['asunto'];
        for ($i=0; $i < count($request['emails'])  ; $i++) 
        { 
            $email = $request['emails'][$i];
            Mail::to($email)->send(new PDFMail(
                $asunto,
                $file
            ));
        }
      }
      
       return 'ok';
    }

    //Funcion para graficas
    public function reporteGraficos (Request $request) 
    {
        $status =  Statu::select('status.*')
        ->with(['status_hijos' => function ($query)
        {
            $query->select('status.*')
            ->whereNotNull('status.status_padre')
            ->groupBy('status.id')
            ->with(
                'confirmacionesDts' 
                );
        }])
        ->whereNull('status.status_padre')
        ->get();

        $plataformas = Plataforma::select('plataformas.*')
        ->where('plataformas.activo','=',1)
        ->with(
            'confirmacionesDts' 
            )
        ->get();
       
        return Inertia::render('Graficas/Graficas.index',[
          'status' => $status,
          'plataformas' => $plataformas
        ]);
    }
}
