<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoExperienciaDocenciaRequest;
use App\Http\Requests\UpdateLegajoExperienciaDocenciaRequest;
use App\Models\Empleado;
use App\Models\LegajoExperienciaDocencia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LegajoExperienciaDocenciaController extends Controller
{
    public function index(Request $request, Empleado $empleado): JsonResponse
    {
        $query = $empleado->docencias()->with(['pais'])->orderByDesc('fecha_inicio');
        if ($request->has('estado')) $query->where('estado', $request->estado);
        return response()->json($query->get());
    }

    public function show(Empleado $empleado, LegajoExperienciaDocencia $docencia): JsonResponse
    {
        return response()->json(['data' => $docencia]);
    }

    public function store(StoreLegajoExperienciaDocenciaRequest $request, Empleado $empleado): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/docencia", 'public');
            $data['archivo_path'] = $path;
        }

        $docencia = $empleado->docencias()->create($data);

        return response()->json(['message' => 'Experiencia docente registrada', 'data' => $docencia], 201);
    }

    public function update(UpdateLegajoExperienciaDocenciaRequest $request, Empleado $empleado, LegajoExperienciaDocencia $docencia): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            if ($docencia->archivo_path) {
                Storage::disk('public')->delete($docencia->archivo_path);
            }
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/docencia", 'public');
            $data['archivo_path'] = $path;
        }

        $docencia->update($data);
        return response()->json(['message' => 'Experiencia docente actualizada', 'data' => $docencia]);
    }

    public function destroy(Empleado $empleado, LegajoExperienciaDocencia $docencia): JsonResponse
    {
        if ($docencia->archivo_path) {
            Storage::disk('public')->delete($docencia->archivo_path);
        }
        $docencia->delete();
        return response()->json(['message' => 'Experiencia docente eliminada']);
    }

}
