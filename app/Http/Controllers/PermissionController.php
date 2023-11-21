<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\RolesPermission;
use Illuminate\Http\Request;

class PermissionController extends Controller
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
        Permission::updateOrCreate([
            'nombre' => $request['nombre'],
            'is_acceso' => 0
         ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }

    public function getPermisosByRol(Request $request)
    {
      $permissions = RolesPermission::select('roles_permissions.permission_id')
      ->where('roles_permissions.role_id','=',$request['rol'])
      ->get();
      
      return response()->json([
        'permissonId' => $permissions->pluck('permission_id')
    ]);
      
    }
}
