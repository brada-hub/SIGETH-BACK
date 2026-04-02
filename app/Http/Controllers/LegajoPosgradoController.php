<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoPosgradoRequest;
use App\Http\Requests\UpdateLegajoPosgradoRequest;
use App\Models\Empleado;
use App\Models\LegajoPosgrado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegajoPosgradoController extends Controller
{
    public function index(Request $request, Empleado $empleado): JsonResponse
    {
        $query = $empleado->posgrados()->with(['pais']);
        if ($request->has('estado')) $query->where('estado', $request->estado);
        return response()->json($query->orderByDesc('año_titulacion')->get());
    }

    public function show(Empleado $empleado, LegajoPosgrado $posgrado): JsonResponse
    {
        return response()->json(['data' => $posgrado]);
    }

    public function store(StoreLegajoPosgradoRequest $request, Empleado $empleado): JsonResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/postgrado", 'public');
            $data['archivo_path'] = $path;
        }

        $posgrado = $empleado->posgrados()->create($data);

        return response()->json(['message' => 'Postgrado registrado', 'data' => $posgrado], 201);
    }

    public function update(UpdateLegajoPosgradoRequest $request, Empleado $empleado, LegajoPosgrado $posgrado): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            if ($posgrado->archivo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($posgrado->archivo_path);
            }
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/postgrado", 'public');
            $data['archivo_path'] = $path;
        }

        $posgrado->update($data);
        return response()->json(['message' => 'Postgrado actualizado', 'data' => $posgrado->load(['pais'])]);
    }

    public function destroy(Empleado $empleado, LegajoPosgrado $posgrado): JsonResponse
    {
        $posgrado->delete();
        return response()->json(['message' => 'Posgrado eliminado']);
    }

}
