<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoMembresiasRequest;
use App\Models\Empleado;
use App\Models\LegajoMembresias;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegajoMembresiasController extends Controller
{
    public function index(Request $request, Empleado $empleado): JsonResponse {
        $query = $empleado->membresias();
        if ($request->has('estado')) $query->where('estado', $request->estado);
        return response()->json($query->orderByDesc('año_inicio')->get());
    }
    public function show(Empleado $empleado, LegajoMembresias $membresia): JsonResponse {
        return response()->json(['data' => $membresia]);
    }
    public function store(StoreLegajoMembresiasRequest $request, Empleado $empleado): JsonResponse {
        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/membresias", 'public');
            $data['archivo_path'] = $path;
        }

        $membresia = $empleado->membresias()->create($data);
        return response()->json(['message' => 'Membresía registrada', 'data' => $membresia], 201);
    }
    public function update(Request $request, Empleado $empleado, LegajoMembresias $membresia): JsonResponse {
        $data = $request->all();

        if ($request->hasFile('archivo')) {
            if ($membresia->archivo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($membresia->archivo_path);
            }
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/membresias", 'public');
            $data['archivo_path'] = $path;
        }

        $membresia->update($data);
        return response()->json(['message' => 'Membresía actualizada', 'data' => $membresia]);
    }
    public function destroy(Empleado $empleado, LegajoMembresias $membresia): JsonResponse {
        $membresia->delete();
        return response()->json(['message' => 'Membresía eliminada']);
    }
}
