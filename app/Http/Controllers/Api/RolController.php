<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::withCount('users')->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:roles,nombre',
        ]);

        $rol = Rol::create($request->only(['nombre', 'descripcion']));

        // Asignar permisos
        if ($request->permission_ids) {
            \DB::table('role_has_permissions')->where('role_id', $rol->id)->delete();
            foreach ($request->permission_ids as $permId) {
                \DB::table('role_has_permissions')->insert([
                    'role_id' => $rol->id,
                    'permission_id' => $permId,
                ]);
            }
        }

        return response()->json($rol, 201);
    }

    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);
        $rol->update($request->only(['nombre', 'descripcion']));

        // Actualizar permisos
        if ($request->has('permission_ids')) {
            \DB::table('role_has_permissions')->where('role_id', $rol->id)->delete();
            foreach ($request->permission_ids as $permId) {
                \DB::table('role_has_permissions')->insert([
                    'role_id' => $rol->id,
                    'permission_id' => $permId,
                ]);
            }
        }

        return response()->json($rol);
    }

    public function show($id)
    {
        $rol = Rol::findOrFail($id);
        $permisos = \DB::table('role_has_permissions')
            ->where('role_id', $id)
            ->pluck('permission_id')
            ->toArray();
        $rol->permission_ids = $permisos;
        return response()->json($rol);
    }

    public function destroy($id)
    {
        Rol::findOrFail($id)->delete();
        return response()->json(['message' => 'Rol eliminado']);
    }
}
