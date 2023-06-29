<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AutenticatheController extends Controller
{
    //
    public function login (Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
     
        //$user = User::where('email', $request->email)->first();
        $user = User::select(
            'users.*',
            'ubicaciones.id as ubicacion_id',
            'ubicaciones.nombre_ubicacion'
        )
        ->leftJoin('user_ubicaciones','user_ubicaciones.user_id','users.id')
        ->leftJoin('ubicaciones','user_ubicaciones.ubicacion_id','ubicaciones.id')
        ->where('email', $request->email)
        ->first();
        
     
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas'],
            ]);
        }
     
        return [$user->createToken($request->device_name)->plainTextToken, $user];
    }
}
