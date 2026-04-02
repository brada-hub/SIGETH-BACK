<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoProduccionIntelectualRequest;
use App\Models\Empleado;
use App\Models\LegajoProduccionIntelectual;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegajoProduccionIntelectualController extends Controller
{
    public function index(Request $request, Empleado $empleado): JsonResponse {
        $query = $empleado->producciones()->with(['pais']);
        if ($request->has('estado')) $query->where('estado', $request->estado);
        return response()->json($query->orderByDesc('año')->get());
    }
    public function show(Empleado $empleado, LegajoProduccionIntelectual $produccion): JsonResponse {
        return response()->json(['data' => $produccion]);
    }
    public function store(StoreLegajoProduccionIntelectualRequest $request, Empleado $empleado): JsonResponse {
        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/producciones", 'public');
            $data['archivo_path'] = $path;
        }

        $produccion = $empleado->producciones()->create($data);
        return response()->json(['message' => 'Producción intelectual registrada', 'data' => $produccion], 201);
    }
    public function update(Request $request, Empleado $empleado, LegajoProduccionIntelectual $produccion): JsonResponse {
        $data = $request->all();

        if ($request->hasFile('archivo')) {
            if ($produccion->archivo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($produccion->archivo_path);
            }
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/producciones", 'public');
            $data['archivo_path'] = $path;
        }

        $produccion->update($data);
        return response()->json(['message' => 'Producción intelectual actualizada', 'data' => $produccion]);
    }
    public function destroy(Empleado $empleado, LegajoProduccionIntelectual $produccion): JsonResponse {
        $produccion->delete();
        return response()->json(['message' => 'Producción intelectual eliminada']);
    }
}
