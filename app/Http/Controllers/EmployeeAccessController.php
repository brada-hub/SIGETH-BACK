<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\JsonResponse;

class EmployeeAccessController extends Controller
{
    /**
     * Verifica la identidad del empleado mediante CI y fecha de nacimiento
     * para otorgar un token de acceso temporal al legajo.
     */
    public function verificarIdentidad(Request $request): JsonResponse
    {
        $request->validate([
            'ci' => 'required|string',
            'ci_expedicion' => 'required|string',
            'fecha_nacimiento' => 'required|date',
        ]);

        $key = 'employee-verify:' . $request->ip();

        // Aplicar Rate Limiting: 5 intentos por hora por IP
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'error' => "Demasiados intentos. Por seguridad, intente nuevamente en " . ceil($seconds / 60) . " minutos."
            ], 429);
        }

        // 1. Buscar a la persona por datos de identidad
        $persona = Persona::where('ci', $request->ci)
            ->where('ci_expedicion', $request->ci_expedicion)
            ->where('fecha_nacimiento', $request->fecha_nacimiento)
            ->first();

        if (!$persona) {
            RateLimiter::hit($key, 3600); // 1 hora
            return response()->json(['error' => 'Datos incorrectos'], 401);
        }

        // 2. Si existe la persona, asegurar que tenga un registro de Empleado
        $empleado = Empleado::firstOrCreate(
            ['persona_id' => $persona->id],
            ['activo' => 1]
        );

        // 3. Asegurar que tenga un registro de Usuario para generar tokens de Sanctum
        $user = $empleado->user;
        if (!$user) {
            $user = \App\Models\User::create([
                'username' => 'EMP_' . $persona->ci,
                'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                'empleado_id' => $empleado->id,
                'activo' => 1
            ]);
        }

        // 4. Éxito: Limpiar contador de intentos y generar token
        RateLimiter::clear($key);

        $token = $user->createToken(
            'empleado-access',
            ['legajo:read', 'legajo:write'],
            now()->addHours(8)
        )->plainTextToken;

        return response()->json([
            'token' => $token,
            'empleado_id' => $empleado->id,
            'nombre' => $persona->nombres . ' ' . $persona->primer_apellido
        ]);
    }
}
