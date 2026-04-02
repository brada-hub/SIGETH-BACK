<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoFormacionAcademicaRequest;
use App\Http\Requests\UpdateLegajoFormacionAcademicaRequest;
use App\Models\Empleado;
use App\Models\LegajoFormacionAcademica;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegajoFormacionAcademicaController extends Controller
{
    public function index(Request $request, Empleado $empleado): JsonResponse
    {
        $query = $empleado->formaciones()->with(['nivel', 'pais']);
        if ($request->has('estado')) $query->where('estado', $request->estado);
        return response()->json($query->orderByDesc('fecha_diploma')->get());
    }

    public function show(Empleado $empleado, LegajoFormacionAcademica $formacion): JsonResponse
    {
        return response()->json(['data' => $formacion]);
    }

    public function store(StoreLegajoFormacionAcademicaRequest $request, Empleado $empleado): JsonResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('archivo_diploma')) {
            $path = $request->file('archivo_diploma')->store("legajo/{$empleado->id}/formacion", 'public');
            $data['archivo_diploma_path'] = $path;
        }

        if ($request->hasFile('archivo_titulo')) {
            $path = $request->file('archivo_titulo')->store("legajo/{$empleado->id}/formacion", 'public');
            $data['archivo_titulo_path'] = $path;
        }

        $formacion = $empleado->formaciones()->create($data);

        return response()->json(['message' => 'Formación registrada', 'data' => $formacion], 201);
    }

    public function update(UpdateLegajoFormacionAcademicaRequest $request, Empleado $empleado, LegajoFormacionAcademica $formacion): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('archivo_diploma')) {
            if ($formacion->archivo_diploma_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($formacion->archivo_diploma_path);
            }
            $path = $request->file('archivo_diploma')->store("legajo/{$empleado->id}/formacion", 'public');
            $data['archivo_diploma_path'] = $path;
        }

        if ($request->hasFile('archivo_titulo')) {
            if ($formacion->archivo_titulo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($formacion->archivo_titulo_path);
            }
            $path = $request->file('archivo_titulo')->store("legajo/{$empleado->id}/formacion", 'public');
            $data['archivo_titulo_path'] = $path;
        }

        $formacion->update($data);
        return response()->json(['message' => 'Formación actualizada', 'data' => $formacion->load(['nivel', 'pais'])]);
    }

    public function destroy(Empleado $empleado, LegajoFormacionAcademica $formacion): JsonResponse
    {
        $formacion->delete();
        return response()->json(['message' => 'Formación eliminada']);
    }

}
