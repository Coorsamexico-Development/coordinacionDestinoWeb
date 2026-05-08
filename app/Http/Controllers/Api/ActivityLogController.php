<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    /**
     * Registrar una falla o actividad enviada desde el cliente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'log_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'properties' => 'nullable|array',
            'event' => 'nullable|string|max:255',
        ]);
        Log::info('Se esta guardando una actividad.', [
            'log_name' => $request->input('log_name'),
            'description' => $request->input('description'),
            'properties' => $request->input('properties'),
            'event' => $request->input('event'),
        ]);

        // Registrar la actividad usando el helper de Spatie
        $activity = activity($request->input('log_name', 'client_error'))
            ->withProperties($request->input('properties', []));

        if ($request->has('event')) {
            $activity->event($request->input('event'));
        }

        $activity->log($request->input('description'));

        return response()->json([
            'status' => 'success',
            'message' => 'Falla registrada correctamente.',
        ], 201);
    }
}
