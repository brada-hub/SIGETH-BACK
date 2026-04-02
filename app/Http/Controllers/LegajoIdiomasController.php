<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegajoIdiomasRequest;
use App\Models\Empleado;
use App\Models\LegajoIdiomas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegajoIdiomasController extends Controller
{
    public function index(Request $request, Empleado $empleado): JsonResponse {
        $query = $empleado->idiomas()->with(['idiomaCatalogo']);
        if ($request->has('estado')) $query->where('estado', $request->estado);
        return response()->json($query->get()->sortBy(fn($i) => $i->idiomaCatalogo->nombre)->values());
    }
    public function show(Empleado $empleado, LegajoIdiomas $idioma): JsonResponse {
        return response()->json(['data' => $idioma]);
    }
    public function store(StoreLegajoIdiomasRequest $request, Empleado $empleado): JsonResponse {
        $data = $request->validated();
        if ($empleado->idiomas()->where('idioma_id', $data['idioma_id'])->exists()) {
            return response()->json(['message' => 'El empleado ya tiene registrado este idioma'], 422);
        }
        

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/idiomas", 'public');
            $data['archivo_path'] = $path;
        }

        $idioma = $empleado->idiomas()->create($data);
        return response()->json(['message' => 'Idioma registrado', 'data' => $idioma], 201);
    }
    public function update(Request $request, Empleado $empleado, LegajoIdiomas $idioma): JsonResponse {
        $data = $request->all();

        if ($request->hasFile('archivo')) {
            if ($idioma->archivo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($idioma->archivo_path);
            }
            $path = $request->file('archivo')->store("legajos/{$empleado->id}/idiomas", 'public');
            $data['archivo_path'] = $path;
        }

        $idioma->update($data);
        return response()->json(['message' => 'Idioma actualizado', 'data' => $idioma]);
    }
    public function destroy(Empleado $empleado, LegajoIdiomas $idioma): JsonResponse {
        $idioma->delete();
        return response()->json(['message' => 'Idioma eliminado']);
    }
}
