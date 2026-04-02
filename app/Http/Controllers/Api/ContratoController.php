<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Persona;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    /**
     * Listar todos los contratos (con filtros opcionales).
     */
    public function index(Request $request)
    {
        $query = Contrato::with(['empleado.persona', 'cargo.tipoPersonal', 'sede']);

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('empleado.persona', function ($q) use ($s) {
                $q->where('nombres', 'like', "%{$s}%")
                  ->orWhere('primer_apellido', 'like', "%{$s}%")
                  ->orWhere('ci', 'like', "%{$s}%");
            });
        }

        return response()->json($query->orderByDesc('fecha_inicio')->get());
    }

    /**
     * Registrar un nuevo contrato para una persona.
     * Si ya tiene uno Vigente, se finaliza automáticamente.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'persona_id'      => 'required|exists:personas,id',
            'cargo_id'        => 'required|exists:cargos,id',
            'sede_id'         => 'required|exists:sedes,id',
            'nro_contrato'    => 'nullable|string|unique:contratos,nro_contrato',
            'fecha_inicio'    => 'required|date',
            'fecha_fin'       => 'nullable|date|after_or_equal:fecha_inicio',
            'salario'         => 'nullable|numeric|min:0',
            'estado'          => 'nullable|in:Vigente,Finalizado,Rescindido',
            'observaciones'   => 'nullable|string',
        ]);

        // Obtener el ID de empleado de esta persona
        $empleado = \App\Models\Empleado::where('persona_id', $data['persona_id'])->first();
        if (!$empleado) {
            // Si no existe el registro de empleado, lo creamos
            $empleado = \App\Models\Empleado::create([
                'persona_id' => $data['persona_id'],
                'sede_id' => $data['sede_id'],
                'activo' => 1
            ]);
        }

        $data['empleado_id'] = $empleado->id;
        $data['estado'] = $data['estado'] ?? 'Vigente';

        // Finalizar contrato vigente anterior si existe
        if ($data['estado'] === 'Vigente') {
            Contrato::where('empleado_id', $empleado->id)
                    ->where('estado', 'Vigente')
                    ->update(['estado' => 'Finalizado']);
        }

        $contrato = Contrato::create($data);

        return response()->json(
            $contrato->load(['persona', 'cargo.tipoPersonal', 'sede']),
            201
        );
    }

    /**
     * Ver un contrato específico.
     */
    public function show($id)
    {
        $contrato = Contrato::with(['persona', 'cargo.tipoPersonal', 'sede'])
                            ->findOrFail($id);
        return response()->json($contrato);
    }

    /**
     * Actualizar un contrato (ej: cambiar estado, corregir sueldo, etc.).
     */
    public function update(Request $request, $id)
    {
        $contrato = Contrato::findOrFail($id);

        $data = $request->validate([
            'cargo_id'        => 'sometimes|exists:cargos,id',
            'sede_id'         => 'sometimes|exists:sedes,id',
            'nro_contrato'    => "nullable|string|unique:contratos,nro_contrato,{$id}",
            'fecha_inicio'    => 'sometimes|date',
            'fecha_fin'       => 'nullable|date',
            'salario'         => 'nullable|numeric|min:0',
            'estado'          => 'nullable|in:Vigente,Finalizado,Rescindido',
            'observaciones'   => 'nullable|string',
        ]);

        $contrato->update($data);

        return response()->json(
            $contrato->load(['empleado.persona', 'cargo.tipoPersonal', 'sede'])
        );
    }

    /**
     * Listar los contratos de una persona específica.
     */
    public function porPersona($personaId)
    {
        $persona = Persona::findOrFail($personaId);
        $empleado = \App\Models\Empleado::where('persona_id', $personaId)->first();

        if (!$empleado) {
            return response()->json(['persona' => $persona->only(['id', 'nombre_completo', 'ci']), 'contratos' => []]);
        }

        $contratos = Contrato::with(['cargo.tipoPersonal', 'sede'])
            ->where('empleado_id', $empleado->id)
            ->orderByDesc('fecha_inicio')
            ->get();

        return response()->json([
            'persona'   => $persona->only(['id', 'nombre_completo', 'ci']),
            'contratos' => $contratos,
        ]);
    }

    /**
     * Soft-delete de un contrato.
     */
    public function destroy($id)
    {
        Contrato::findOrFail($id)->delete();
        return response()->json(['message' => 'Contrato eliminado']);
    }
}
