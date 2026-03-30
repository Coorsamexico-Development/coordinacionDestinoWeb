<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AutenticatheController extends Controller
{
    //
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        //$user = User::where('email', $request->email)->first();
        $user = User::selectApp()->where('email', $request->email)->first();


        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken($request->device_name)->plainTextToken,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout exitoso'
        ], 200);
    }

    public function validToken(Request $request)  {
        $user = $request->user();
        if ($user) {
            return response()->json([
                'token' => str($request->header('Authorization'))->replace('Bearer ', ''),
            'user' => User::selectApp()->where('users.id', $user->id)->first()
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token invalido'
            ], 401);
        }
    }
}
