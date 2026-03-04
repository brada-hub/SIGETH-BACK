<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['sede', 'applications', 'rol'])->orderBy('nombres')->get();

        // Agregar permission_ids directos a cada user
        $users->each(function ($user) {
            $user->direct_permission_ids = \DB::table('model_has_permissions')
                ->where('model_id', $user->id)
                ->where('model_type', 'App\\Models\\User')
                ->pluck('permission_id')
                ->toArray();
        });

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ci' => 'required|unique:users,ci',
            'nombres' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'ci' => $request->ci,
            'nombres' => $request->nombres,
            'apellidos' => trim(($request->apellido_paterno ?? '') . ' ' . ($request->apellido_materno ?? '')),
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone ?? '00000000',
            'sede_id' => $request->sede_id,
            'jurisdiccion' => $request->jurisdiccion,
            'rol_id' => $request->rol_id,
            'activo' => $request->activo ?? true,
            'must_change_password' => true,
        ]);

        // Asignar aplicaciones
        if ($request->application_ids) {
            foreach ($request->application_ids as $appId) {
                $user->applications()->attach($appId, [
                    'role' => 'admin',
                    'permissions' => json_encode(['all'])
                ]);
            }
        }

        // Permisos directos del usuario
        if ($request->has('direct_permission_ids')) {
            $this->syncUserPermissions($user->id, $request->direct_permission_ids);
        }

        return response()->json($user->load(['sede', 'applications', 'rol']), 201);
    }

    public function show($id)
    {
        $user = User::with(['sede', 'applications', 'rol'])->findOrFail($id);
        $user->direct_permission_ids = \DB::table('model_has_permissions')
            ->where('model_id', $id)
            ->where('model_type', 'App\\Models\\User')
            ->pluck('permission_id')
            ->toArray();
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $updateData = $request->only([
            'nombres', 'apellido_paterno', 'apellido_materno',
            'email', 'phone', 'sede_id', 'jurisdiccion', 'rol_id', 'activo'
        ]);

        // Sincronizar 'apellidos' legacy con apellido_paterno + apellido_materno
        if ($request->has('apellido_paterno') || $request->has('apellido_materno')) {
            $updateData['apellidos'] = trim(
                ($request->apellido_paterno ?? $user->apellido_paterno ?? '') . ' ' .
                ($request->apellido_materno ?? $user->apellido_materno ?? '')
            );
        }

        $user->update($updateData);

        if ($request->has('password') && $request->password) {
            $user->update(['password' => $request->password]);
        }

        // Actualizar aplicaciones
        if ($request->has('application_ids')) {
            $syncData = [];
            foreach ($request->application_ids as $appId) {
                $syncData[$appId] = ['role' => 'admin', 'permissions' => json_encode(['all'])];
            }
            $user->applications()->sync($syncData);
        }

        // Permisos directos del usuario
        if ($request->has('direct_permission_ids')) {
            $this->syncUserPermissions($user->id, $request->direct_permission_ids);
        }

        return response()->json($user->load(['sede', 'applications', 'rol']));
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => $user->ci,
            'must_change_password' => true,
        ]);
        return response()->json(['message' => "Contraseña restablecida al CI: {$user->ci}"]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        \DB::table('model_has_permissions')->where('model_id', $id)->where('model_type', 'App\\Models\\User')->delete();
        $user->applications()->detach();
        $user->tokens()->delete();
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }

    private function syncUserPermissions($userId, array $permissionIds)
    {
        \DB::table('model_has_permissions')
            ->where('model_id', $userId)
            ->where('model_type', 'App\\Models\\User')
            ->delete();

        foreach ($permissionIds as $permId) {
            \DB::table('model_has_permissions')->insert([
                'permission_id' => $permId,
                'model_type' => 'App\\Models\\User',
                'model_id' => $userId,
            ]);
        }
    }
}
