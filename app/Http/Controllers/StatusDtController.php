<?php

namespace App\Http\Controllers;

use App\Models\Statu;
use App\Models\StatusDt;
use Illuminate\Http\Request;

class StatusDtController extends Controller
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
    public function show(StatusDt $statusDt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatusDt $statusDt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StatusDt $statusDt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatusDt $statusDt)
    {
        //
    }

    public function showHistorico(Request $request)
    {
        $historico =  StatusDt::select('status_dts.*',
        'status.nombre as status',
        'status.color as color')
        ->join('status', 'status_dts.status_id','status.id')
        ->where('status_dts.confirmacion_dt_id','=',$request['id'])
        ->get();

        $status_Hijos = Statu::select('status.*')
        ->whereNotNull('status_padre')
        ->get();

        return ['historico' => $historico, 'status' => $status_Hijos];
    }
}
