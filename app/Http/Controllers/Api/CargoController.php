<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\TipoPersonal;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    /**
     * Listar todos los cargos con su tipo de personal.
     */
    public function index(Request $request)
    {
        $query = Cargo::with('tipoPersonal');

        if ($request->filled('tipo_personal_id')) {
            $query->where('tipo_personal_id', $request->tipo_personal_id);
        }

        if ($request->boolean('activos')) {
            $query->where('activo', true);
        }

        return response()->json($query->orderBy('nombre')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'           => 'required|string|max:150',
            'tipo_personal_id' => 'required|exists:tipos_personal,id',
            'descripcion'      => 'nullable|string',
            'activo'           => 'boolean',
        ]);

        $cargo = Cargo::create($data);

        return response()->json($cargo->load('tipoPersonal'), 201);
    }

    public function update(Request $request, $id)
    {
        $cargo = Cargo::findOrFail($id);

        $data = $request->validate([
            'nombre'           => 'sometimes|required|string|max:150',
            'tipo_personal_id' => 'sometimes|exists:tipos_personal,id',
            'descripcion'      => 'nullable|string',
            'activo'           => 'boolean',
        ]);

        $cargo->update($data);

        return response()->json($cargo->load('tipoPersonal'));
    }

    public function destroy($id)
    {
        $cargo = Cargo::findOrFail($id);

        // No borrar si hay contratos activos asociados
        if ($cargo->contratos()->where('estado_contrato', 'Vigente')->exists()) {
            return response()->json(
                ['message' => 'No se puede eliminar: el cargo tiene contratos vigentes'],
                422
            );
        }

        $cargo->delete();
        return response()->json(['message' => 'Cargo eliminado']);
    }

    // ─── Tipos de Personal ────────────────────────────────────

    public function tiposPersonal()
    {
        return response()->json(TipoPersonal::where('activo', true)->orderBy('nombre')->get());
    }

    public function storeTipoPersonal(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|unique:tipos_personal,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $tipo = TipoPersonal::create($data);
        return response()->json($tipo, 201);
    }

    public function updateTipoPersonal(Request $request, $id)
    {
        $tipo = TipoPersonal::findOrFail($id);
        $data = $request->validate([
            'nombre'      => "sometimes|string|unique:tipos_personal,nombre,{$id}",
            'descripcion' => 'nullable|string',
            'activo'      => 'boolean',
        ]);
        $tipo->update($data);
        return response()->json($tipo);
    }
}
