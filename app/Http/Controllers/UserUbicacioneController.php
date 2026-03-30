<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Ubicacione;
use App\Models\User;
use App\Models\UserUbicacione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserUbicacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $users = User::select(
            'users.*',
            'roles.nombre as role_name'
        )
        ->with('ubicaciones')
            ->leftJoin('roles', 'users.role_id', 'roles.id')
            ->where('users.email', '!=', 'test@coorsamexico.com');

        if ($request->has("busqueda")) {
            if ($request['busqueda'] !== null) {
                $search = strtr($request->busqueda, array("'" => "\\'", "%" => "\\%"));
                $users->where("users.name", "LIKE", "%" . $search . "%")
                    ->orWhere("users.apellido_paterno", "LIKE", "%" . $search . "%")
                    ->orWhere("users.apellido_materno", "LIKE", "%" . $search . "%");
            }
        }

        $roles = Role::all();
        $ubicaciones = Ubicacione::all();

        return Inertia::render('ManageUsers/ManageUsers', [
            'users' => fn() =>  $users->paginate(5),
            'roles' => $roles,
            'ubicaciones' => $ubicaciones
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
        $request->validate([ //validaciones
            'email' => 'required',
            'nombre' => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'contraseña' => 'required',
            'role_id' => 'required',
            'ubicacion_id' => 'required|array'
        ]);

        $user =  User::create([
            'email' => $request['email'],
            'name' => $request['nombre'],
            'apellido_paterno' => $request['ap_paterno'],
            'apellido_materno' => $request['ap_materno'],
            'role_id' => $request['role_id'],
            'password' => Hash::make($request['contraseña'])
        ]);

        $user->ubicaciones()->sync($request['ubicacion_id']);

        return redirect()->back();
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
    public function update(Request $request)
    {
        //
        $request->validate([ //validaciones
            'id' => 'required',
            'email' => 'required',
            'nombre' => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'contraseña' => 'nullable|min:5',
            'role_id' => 'required',
            'ubicacion_id' => 'nullable|array'
        ]);

        $newUser = [
                'email' => $request['email'],
                'name' => $request['nombre'],
                'apellido_paterno' => $request['ap_paterno'],
                'apellido_materno' => $request['ap_materno'],
                'role_id' => $request['role_id'],
        ];

        if($request['contraseña'] != null){
            $newUser['password'] = Hash::make($request['contraseña']);
            
            $userToInvalidate = User::find($request['id']);
            if ($userToInvalidate) {
                // Invalidar API Tokens (Sanctum)
                $userToInvalidate->tokens()->delete();
                
                // Invalidar Sesiones Web (Database Driver)
                DB::table('sessions')->where('user_id', $userToInvalidate->id)->delete();
            }
        }

        $user =  User::where('id', '=', $request['id'])
            ->update($newUser);

        $updatedUser = User::find($request['id']);
        $updatedUser->ubicaciones()->sync($request['ubicacion_id']);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserUbicacione $userUbicacione)
    {
        //
    }
}
