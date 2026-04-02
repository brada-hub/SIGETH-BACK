<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sistema;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Los datos ahora están mayormente en la tabla users o accesibles por persona_id
        $users = User::with(['persona', 'sede', 'roles', 'applications'])->get();

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'nullable|unique:users,username',
            'ci' => 'required|unique:users,ci',
            'nombres' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'username' => $request->username ?? $request->ci,
            'ci' => $request->ci,
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'apellidos' => trim(($request->apellido_paterno ?? '') . ' ' . ($request->apellido_materno ?? '')),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone ?? '00000000',
            'sede_id' => $request->sede_id,
            'jurisdiccion' => $request->jurisdiccion ?? [],
            'rol_id' => $request->rol_id,
            'activo' => $request->activo ?? true,
            'must_change_password' => true,
        ]);

        // Sincronizar aplicaciones (Sistemas a través de user_roles)
        if ($request->has('application_ids')) {
            $this->syncUserApplications($user, $request->application_ids, $request->rol_id);
        }

        // Permisos directos (si se usan)
        if ($request->has('direct_permission_ids')) {
            $this->syncUserPermissions($user->id, $request->direct_permission_ids);
        }

        return response()->json($user->load(['sede', 'applications', 'rol']), 201);
    }

    public function show($id)
    {
        $user = User::with(['sede', 'applications', 'rol'])->findOrFail($id);
        
        // Obtener permisos de model_has_permissions si se usan
        $user->direct_permission_ids = DB::table('model_has_permissions')
            ->where('model_id', $id)
            ->where('model_type', User::class)
            ->pluck('permission_id')
            ->toArray();
            
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $updateData = $request->only([
            'username', 'nombres', 'apellido_paterno', 'apellido_materno',
            'email', 'phone', 'sede_id', 'jurisdiccion', 'rol_id', 'activo'
        ]);

        if ($request->has('ci')) {
            $updateData['ci'] = $request->ci;
        }

        // Actualizar apellidos concatenados
        if ($request->has('apellido_paterno') || $request->has('apellido_materno')) {
            $updateData['apellidos'] = trim(
                ($request->apellido_paterno ?? $user->apellido_paterno ?? '') . ' ' .
                ($request->apellido_materno ?? $user->apellido_materno ?? '')
            );
        }

        $user->update($updateData);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Sincronizar aplicaciones
        if ($request->has('application_ids')) {
            $this->syncUserApplications($user, $request->application_ids, $request->rol_id);
        }

        // Permisos directos
        if ($request->has('direct_permission_ids')) {
            $this->syncUserPermissions($user->id, $request->direct_permission_ids);
        }

        return response()->json($user->load(['sede', 'applications', 'rol']));
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($user->ci),
            'must_change_password' => true,
        ]);
        return response()->json(['message' => "Contraseña restablecida al CI: {$user->ci}"]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        DB::transaction(function () use ($user) {
            DB::table('model_has_permissions')->where('model_id', $user->id)->where('model_type', User::class)->delete();
            DB::table('user_roles')->where('user_id', $user->id)->delete();
            $user->tokens()->delete();
            $user->delete();
        });

        return response()->json(['message' => 'Usuario eliminado']);
    }

    private function syncUserApplications(User $user, array $appIds, $rolId)
    {
        // En este sistema, las apps son 'Sistemas' y el vínculo es a través de 'user_roles'
        // Si no hay rol_id proporcionado, usamos el que ya tiene el usuario o uno por defecto
        $finalRolId = $rolId ?? $user->rol_id;
        
        if (!$finalRolId) {
            // Buscar un rol de Administrador por defecto si no tiene uno
            $finalRolId = DB::table('roles')->where('nombre', 'Administrador')->value('id');
        }

        if ($finalRolId) {
            $syncData = [];
            foreach ($appIds as $appId) {
                $syncData[$appId] = [
                    'rol_id' => $finalRolId,
                    'activo' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            // Sincronizar en la tabla user_roles (sistemas)
            DB::table('user_roles')->where('user_id', $user->id)->delete();
            foreach ($syncData as $sistemaId => $pivotData) {
                DB::table('user_roles')->insert(array_merge(['user_id' => $user->id, 'sistema_id' => $sistemaId], $pivotData));
            }
        }
    }

    private function syncUserPermissions($userId, array $permissionIds)
    {
        DB::table('model_has_permissions')
            ->where('model_id', $userId)
            ->where('model_type', User::class)
            ->delete();

        foreach ($permissionIds as $permId) {
            DB::table('model_has_permissions')->insert([
                'permission_id' => $permId,
                'model_type' => User::class,
                'model_id' => $userId,
            ]);
        }
    }
}
