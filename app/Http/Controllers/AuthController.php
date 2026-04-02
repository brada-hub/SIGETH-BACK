<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Login - Authenticates user and returns token.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)
            ->where('activo', 1)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Las credenciales son incorrectas o el usuario está inactivo.'],
            ]);
        }

        // Cargar relaciones necesarias
        $user->load(['empleado.persona', 'roles.sistema', 'roles.permisos', 'applications']);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Logout - Revokes current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente.'
        ]);
    }

    /**
     * Me - Returns authenticated user details with roles and permissions.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load([
            'empleado.persona',
            'roles.sistema',
            'roles.permisos',
            'applications'
        ]);

        $permissionsBySystem = [];
        foreach ($user->roles as $rol) {
            $sistemaSlug = $rol->sistema ? $rol->sistema->slug : 'global';
            if (!isset($permissionsBySystem[$sistemaSlug])) {
                $permissionsBySystem[$sistemaSlug] = [
                    'sistema' => $rol->sistema ? $rol->sistema->nombre : 'Global',
                    'roles' => [],
                    'permisos' => []
                ];
            }
            $permissionsBySystem[$sistemaSlug]['roles'][] = $rol->nombre;
            foreach ($rol->permisos as $permiso) {
                $permissionsBySystem[$sistemaSlug]['permisos'][] = $permiso->nombre;
            }
        }
        return response()->json([
            'user' => $user,
            'access_metadata' => $permissionsBySystem
        ]);
    }

    /**
     * Change Password - Authenticated user changes their own password.
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'La contraseña actual es incorrecta'], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'must_change_password' => false,
        ]);

        return response()->json(['message' => 'Contraseña actualizada correctamente']);
    }

    /**
     * Force Change Password - For users required to change password on first login.
     */
    public function forceChangePassword(Request $request): JsonResponse
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->new_password),
            'must_change_password' => false,
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada correctamente',
            'user' => $user->fresh()->load(['empleado.persona', 'roles.sistema', 'roles.permisos', 'applications'])
        ]);
    }
}
