<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoBachilleratoRequest;
use App\Http\Requests\UpdateLegajoBachilleratoRequest;
use App\Models\Empleado;
use App\Models\LegajoBachillerato;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegajoBachilleratoController extends Controller
{
    public function show(Empleado $empleado): JsonResponse
    {
        $bachillerato = $empleado->bachiller;
        return response()->json(['data' => $bachillerato]);
    }

    public function store(StoreLegajoBachilleratoRequest $request, Empleado $empleado): JsonResponse
    {
        if ($empleado->bachiller()->exists()) {
            return response()->json([
                'message' => 'El empleado ya tiene un bachillerato registrado.'
            ], 422);
        }

        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/bachillerato", 'public');
            $data['archivo_path'] = $path;
        }

        $bachillerato = $empleado->bachiller()->create($data);

        return response()->json(['message' => 'Bachillerato registrado', 'data' => $bachillerato], 201);
    }

    public function update(UpdateLegajoBachilleratoRequest $request, Empleado $empleado): JsonResponse
    {
        $bachillerato = $empleado->bachiller;
        if (!$bachillerato) return response()->json(['message' => 'No registrado'], 404);

        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            if ($bachillerato->archivo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($bachillerato->archivo_path);
            }
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/bachillerato", 'public');
            $data['archivo_path'] = $path;
        }

        $bachillerato->update($data);
        return response()->json(['message' => 'Bachillerato actualizado', 'data' => $bachillerato]);
    }

}
