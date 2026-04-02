<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoReconocimientoRequest;
use App\Models\Empleado;
use App\Models\LegajoReconocimiento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegajoReconocimientoController extends Controller
{
    public function index(Request $request, Empleado $empleado): JsonResponse {
        $query = $empleado->reconocimientos();
        if ($request->has('estado')) $query->where('estado', $request->estado);
        return response()->json($query->orderByDesc('año')->get());
    }
    public function show(Empleado $empleado, LegajoReconocimiento $reconocimiento): JsonResponse {
        return response()->json(['data' => $reconocimiento]);
    }
    public function store(StoreLegajoReconocimientoRequest $request, Empleado $empleado): JsonResponse {
        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/reconocimientos", 'public');
            $data['archivo_path'] = $path;
        }

        $reconocimiento = $empleado->reconocimientos()->create($data);
        return response()->json(['message' => 'Reconocimiento registrado', 'data' => $reconocimiento], 201);
    }
    public function update(Request $request, Empleado $empleado, LegajoReconocimiento $reconocimiento): JsonResponse {
        $data = $request->all();

        if ($request->hasFile('archivo')) {
            if ($reconocimiento->archivo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($reconocimiento->archivo_path);
            }
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/reconocimientos", 'public');
            $data['archivo_path'] = $path;
        }

        $reconocimiento->update($data);
        return response()->json(['message' => 'Reconocimiento actualizado', 'data' => $reconocimiento]);
    }
    public function destroy(Empleado $empleado, LegajoReconocimiento $reconocimiento): JsonResponse {
        $reconocimiento->delete();
        return response()->json(['message' => 'Reconocimiento eliminado']);
    }
}
