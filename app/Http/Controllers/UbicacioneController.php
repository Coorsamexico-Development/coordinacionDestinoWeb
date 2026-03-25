<?php

namespace App\Http\Controllers;

use App\Models\Ubicacione;
use Illuminate\Http\Request;

class UbicacioneController extends Controller
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
    public function show(Ubicacione $ubicacione)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ubicacione $ubicacione)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ubicacione $ubicacione)
    {
        $request->validate([
            'nombre_ubicacion' => 'required|string|max:255',
        ]);

        $ubicacione->update([
            'nombre_ubicacion' => $request->nombre_ubicacion,
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ubicacione $ubicacione)
    {
        // Check if there are associated records (assuming ConfirmacionDt relates to Ubicacione)
        if (\App\Models\ConfirmacionDt::where('ubicacion_id', $ubicacione->id)->exists()) {
            return redirect()->back()->withErrors(['Ubicacion' => 'No se puede eliminar la ubicación porque tiene confirmaciones (viajes) asociados.']);
        }

        $ubicacione->delete();

        return redirect()->back();
    }

    /**
     * Transfer records to another location and optionally delete this one.
     */
    public function transfer(Request $request, Ubicacione $ubicacione)
    {
        $request->validate([
            'target_ubicacion_id' => 'required|exists:ubicaciones,id',
            'delete_current' => 'boolean'
        ]);

        if ($request->target_ubicacion_id == $ubicacione->id) {
            return redirect()->back()->withErrors(['Transfer' => 'No se puede transferir a la misma ubicación.']);
        }

        // Transfer confirmaciones
        \App\Models\ConfirmacionDt::where('ubicacion_id', $ubicacione->id)
            ->update(['ubicacion_id' => $request->target_ubicacion_id]);

        if ($request->filled('delete_current') && $request->delete_current) {
            $ubicacione->delete();
        }

        return redirect()->back();
    }
}
