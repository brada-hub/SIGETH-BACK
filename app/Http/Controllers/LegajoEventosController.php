<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoEventosRequest;
use App\Models\Empleado;
use App\Models\LegajoEventos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegajoEventosController extends Controller
{
    public function index(Request $request, Empleado $empleado): JsonResponse {
        $query = $empleado->eventos()->with(['pais']);
        if ($request->has('estado')) $query->where('estado', $request->estado);
        return response()->json($query->orderByDesc('año')->get());
    }
    public function show(Empleado $empleado, LegajoEventos $evento): JsonResponse {
        return response()->json(['data' => $evento]);
    }
    public function store(StoreLegajoEventosRequest $request, Empleado $empleado): JsonResponse {
        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/eventos", 'public');
            $data['archivo_path'] = $path;
        }

        $evento = $empleado->eventos()->create($data);
        return response()->json(['message' => 'Evento registrado', 'data' => $evento], 201);
    }
    public function update(Request $request, Empleado $empleado, LegajoEventos $evento): JsonResponse {
        $data = $request->all();

        if ($request->hasFile('archivo')) {
            if ($evento->archivo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($evento->archivo_path);
            }
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/eventos", 'public');
            $data['archivo_path'] = $path;
        }

        $evento->update($data);
        return response()->json(['message' => 'Evento actualizado', 'data' => $evento]);
    }
    public function destroy(Empleado $empleado, LegajoEventos $evento): JsonResponse {
        $evento->delete();
        return response()->json(['message' => 'Evento eliminado']);
    }
}
