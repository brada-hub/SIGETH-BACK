<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoCapacitacionRequest;
use App\Models\Empleado;
use App\Models\LegajoCapacitacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegajoCapacitacionController extends Controller
{
    public function index(Request $request, Empleado $empleado): JsonResponse {
        $query = $empleado->capacitaciones();
        if ($request->has('estado')) $query->where('estado', $request->estado);
        return response()->json($query->orderByDesc('año')->get());
    }
    public function show(Empleado $empleado, LegajoCapacitacion $capacitacion): JsonResponse {
        return response()->json(['data' => $capacitacion]);
    }
    public function store(StoreLegajoCapacitacionRequest $request, Empleado $empleado): JsonResponse {
        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/capacitacion", 'public');
            $data['archivo_path'] = $path;
        }

        $capacitacion = $empleado->capacitaciones()->create($data);
        return response()->json(['message' => 'Capacitación registrada', 'data' => $capacitacion], 201);
    }
    public function update(Request $request, Empleado $empleado, LegajoCapacitacion $capacitacion): JsonResponse {
        $data = $request->all();

        if ($request->hasFile('archivo')) {
            if ($capacitacion->archivo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($capacitacion->archivo_path);
            }
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/capacitacion", 'public');
            $data['archivo_path'] = $path;
        }

        $capacitacion->update($data);
        return response()->json(['message' => 'Capacitación actualizada', 'data' => $capacitacion]);
    }
    public function destroy(Empleado $empleado, LegajoCapacitacion $capacitacion): JsonResponse {
        $capacitacion->delete();
        return response()->json(['message' => 'Capacitación eliminada']);
    }
}
