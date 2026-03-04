<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'ci' => 'required',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('ci', $request->ci)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $token = $user->createToken('sso-token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load(['sede', 'applications'])
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user()->load(['sede', 'applications']));
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!\Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'La contraseña actual es incorrecta'], 422);
        }

        $user->update([
            'password' => $request->new_password,
            'must_change_password' => false,
        ]);

        return response()->json(['message' => 'Contraseña actualizada correctamente']);
    }

    public function forceChangePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!$user->must_change_password) {
            return response()->json(['message' => 'No es necesario cambiar la contraseña'], 400);
        }

        $user->update([
            'password' => $request->new_password,
            'must_change_password' => false,
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada correctamente',
            'user' => $user->fresh()->load(['sede', 'applications'])
        ]);
    }
}
