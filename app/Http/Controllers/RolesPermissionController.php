<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolesPermission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RolesPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $roles = Role::select('roles.*')
        ->get();

        $permisos = Permission::select('permissions.*')
        ->get();

        return Inertia::render('RolesPermisos/RolesPermisos',[
          'roles' => $roles,
          'permisos' => $permisos
        ]);
    }

    public function setPermission (Request $request)
    {
        $role = Role::select('roles.*')
        ->where('id','=',$request['rol'])
        ->first();

        if ($request['checked']) 
        {
            $role->permissions()->attach([$request['permission']]);
        } else {
            $role->permissions()->detach([$request['permission']]);
        }
        return response()->json([
            'message' => 'ok'
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
    public function show(RolesPermission $rolesPermission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RolesPermission $rolesPermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RolesPermission $rolesPermission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RolesPermission $rolesPermission)
    {
        //
    }
}
