<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContratoRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Models\Contrato;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Contrato::with(['empleado.persona', 'cargo', 'tipoContrato', 'sede']);

            if ($request->has('empleado_id')) {
                $query->where('empleado_id', $request->empleado_id);
            }

            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }

            $contratos = $query->latest('fecha_inicio')->paginate(15);

            return response()->json($contratos);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al listar contratos', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContratoRequest $request): JsonResponse
    {
        try {
            $contrato = Contrato::create($request->validated());
            
            return response()->json([
                'message' => 'Contrato creado exitosamente',
                'data' => $contrato->load(['cargo', 'tipoContrato'])
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear contrato', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contrato $contrato): JsonResponse
    {
        try {
            $contrato->load(['empleado.persona', 'cargo', 'tipoContrato', 'sede']);
            
            $data = $contrato->toArray();
            $data['esta_vigente'] = $contrato->esta_vigente;

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => 'Contrato no encontrado', 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContratoRequest $request, Contrato $contrato): JsonResponse
    {
        try {
            $contrato->update($request->validated());

            return response()->json([
                'message' => 'Contrato actualizado exitosamente',
                'data' => $contrato->fresh(['cargo', 'tipoContrato'])
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar contrato', 'message' => $e->getMessage()], 500);
        }
    }
}
