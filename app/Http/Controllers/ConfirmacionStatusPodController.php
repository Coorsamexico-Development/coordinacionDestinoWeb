<?php

namespace App\Http\Controllers;

use App\Models\confirmacionStatusPod;
use Illuminate\Http\Request;

class ConfirmacionStatusPodController extends Controller
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

    public function saveStatusPodPorConfirmacion (Request $request)
    {
      //desactivamos los demas registros con misma confirmacion
      confirmacionStatusPod::where('confirmacion_dt_id','=',$request['confirmacion']) 
      ->update([
         'activo' => 0
      ]);

      confirmacionStatusPod::create([
         'confirmacion_dt_id' => $request['confirmacion'],
         'status_pod_id' => $request['status'],
      ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(confirmacionStatusPod $confirmacionStatusPod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(confirmacionStatusPod $confirmacionStatusPod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, confirmacionStatusPod $confirmacionStatusPod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(confirmacionStatusPod $confirmacionStatusPod)
    {
        //
    }
}
