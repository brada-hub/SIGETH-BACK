<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoExperienciaProfesionalRequest;
use App\Http\Requests\UpdateLegajoExperienciaProfesionalRequest;
use App\Models\Empleado;
use App\Models\LegajoExperienciaProfesional;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LegajoExperienciaProfesionalController extends Controller
{
    public function index(Request $request, Empleado $empleado): JsonResponse
    {
        $query = $empleado->experiencias()->with(['pais'])->orderByDesc('fecha_inicio');
        if ($request->has('estado')) $query->where('estado', $request->estado);
        return response()->json($query->get());
    }

    public function show(Empleado $empleado, LegajoExperienciaProfesional $experiencia): JsonResponse
    {
        return response()->json(['data' => $experiencia]);
    }

    public function store(StoreLegajoExperienciaProfesionalRequest $request, Empleado $empleado): JsonResponse
    {
        $data = $request->validated();
        $data['es_trabajo_actual'] = filter_var($request->es_trabajo_actual, FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/profesional", 'public');
            $data['archivo_path'] = $path;
        }

        $experiencia = $empleado->experiencias()->create($data);

        return response()->json(['message' => 'Experiencia profesional registrada', 'data' => $experiencia], 201);
    }

    public function update(UpdateLegajoExperienciaProfesionalRequest $request, Empleado $empleado, LegajoExperienciaProfesional $experiencia): JsonResponse
    {
        $data = $request->validated();
        
        if ($request->has('es_trabajo_actual')) {
            $data['es_trabajo_actual'] = filter_var($request->es_trabajo_actual, FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('archivo')) {
            if ($experiencia->archivo_path) {
                Storage::disk('public')->delete($experiencia->archivo_path);
            }
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/profesional", 'public');
            $data['archivo_path'] = $path;
        }

        $experiencia->update($data);
        return response()->json(['message' => 'Experiencia profesional actualizada', 'data' => $experiencia]);
    }

    public function destroy(Empleado $empleado, LegajoExperienciaProfesional $experiencia): JsonResponse
    {
        if ($experiencia->archivo_path) {
            Storage::disk('public')->delete($experiencia->archivo_path);
        }
        $experiencia->delete();
        return response()->json(['message' => 'Experiencia profesional eliminada']);
    }

}
