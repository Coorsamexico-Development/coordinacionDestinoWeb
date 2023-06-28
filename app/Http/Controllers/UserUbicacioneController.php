<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserUbicacione;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserUbicacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::select('users.*',
        'ubicaciones.nombre_ubicacion as ubicacion')
        ->leftJoin('user_ubicaciones','user_ubicaciones.user_id','users.id')
        ->leftJoin('ubicaciones', 'user_ubicaciones.ubicacion_id','ubicaciones.id')
        ->get();
        return Inertia::render('ManageUsers/ManageUsers',[
           'users' => $users
        ]);
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
    public function show(UserUbicacione $userUbicacione)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserUbicacione $userUbicacione)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserUbicacione $userUbicacione)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserUbicacione $userUbicacione)
    {
        //
    }
}
