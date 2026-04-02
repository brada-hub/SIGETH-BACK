<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpleadoRequest;
use App\Http\Requests\UpdateEmpleadoRequest;
use App\Models\Empleado;
use App\Models\Persona;
use App\Models\Contrato;
use App\Models\Cargo;
use App\Models\TipoContrato;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Empleado::with(['persona', 'sede', 'areaTrabajo']);

            // Filtros
            if ($request->has('sede_id')) {
                $query->where('sede_id', $request->sede_id);
            }

            if ($request->has('area_id')) {
                $query->where('area_trabajo_id', $request->area_id);
            }

            if ($request->has('activo')) {
                $query->where('activo', $request->activo);
            }

            // Búsqueda por nombre o CI via persona
            if ($request->has('search')) {
                $search = $request->search;
                $query->whereHas('persona', function ($q) use ($search) {
                    $q->where('nombres', 'like', "%{$search}%")
                      ->orWhere('primer_apellido', 'like', "%{$search}%")
                      ->orWhere('segundo_apellido', 'like', "%{$search}%")
                      ->orWhere('ci', 'like', "%{$search}%");
                });
            }

            $empleados = $query->paginate(15);

            return response()->json($empleados);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al listar empleados', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmpleadoRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $empleado = DB::transaction(function () use ($data) {
                // Crear Persona
                $persona = Persona::create([
                    'primer_apellido' => $data['primer_apellido'],
                    'segundo_apellido' => $data['segundo_apellido'] ?? null,
                    'nombres'         => $data['nombres'],
                    'ci'              => $data['ci'],
                    'ci_expedicion'   => $data['ci_expedicion'] ?? null,
                    'fecha_nacimiento'=> $data['fecha_nacimiento'] ?? null,
                    'genero_id'       => $data['genero_id'] ?? null,
                    'estado_civil_id' => $data['estado_civil_id'] ?? null,
                    'nacionalidad_id' => $data['nacionalidad_id'] ?? null,
                    'correo_personal' => $data['correo_personal'] ?? null,
                    'celular_personal'=> $data['celular_personal'] ?? null,
                ]);

                // Crear Empleado
                $empleado = Empleado::create([
                    'persona_id'           => $persona->id,
                    'correo_institucional' => $data['correo_institucional'] ?? null,
                    'celular_institucional'=> $data['celular_institucional'] ?? null,
                    'sede_id'              => $data['sede_id'] ?? null,
                    'area_trabajo_id'      => $data['area_trabajo_id'] ?? null,
                    'area_dependencia_id'  => $data['area_dependencia_id'] ?? null,
                    'tipo_seguro_id'       => $data['tipo_seguro_id'] ?? null,
                    'tipo_planilla_id'     => $data['tipo_planilla_id'] ?? null,
                    'fecha_ingreso'        => $data['fecha_ingreso'] ?? null,
                    'num_seguro_cns'       => $data['num_seguro_cns'] ?? null,
                    'num_seguro_afp'       => $data['num_seguro_afp'] ?? null,
                    'activo'               => 1,
                ]);

                // Crear Contrato Inicial si se enviaron datos
                if (isset($data['cargo_id']) || isset($data['fecha_inicio'])) {
                    Contrato::create([
                        'empleado_id'      => $empleado->id,
                        'cargo_id'         => $data['cargo_id'] ?? null,
                        'tipo_contrato_id' => $data['tipo_contrato_id'] ?? null,
                        'sede_id'          => $data['sede_id'] ?? null,
                        'nro_contrato'     => $data['nro_contrato'] ?? null,
                        'fecha_inicio'     => $data['fecha_inicio'] ?? now(),
                        'fecha_fin'        => $data['fecha_fin'] ?? null,
                        'salario'          => $data['salario'] ?? 0,
                        'bono_frontera'    => $data['bono_frontera'] ?? 0,
                        'estado'           => 'Vigente',
                    ]);
                }

                return $empleado;
            });

            return response()->json([
                'message' => 'Empleado creado exitosamente',
                'data' => $empleado->load('persona')
            ], 201);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear empleado', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado): JsonResponse
    {
        $empleado->load([
            'persona', 
            'sede', 
            'areaTrabajo', 
            'areaDependencia', 
            'posiciones.posicion',
            'tipoSeguro',
            'tipoPlanilla'
        ]);

        return response()->json([
            'data' => array_merge($empleado->toArray(), [
                'nro_hijos'        => $empleado->nroHijos(),
                'nombre_completo'  => $empleado->persona->nombre_completo,
                'contratos_vigentes' => $empleado->contratos()
                                        ->where('estado', 'Vigente')
                                        ->get(),
            ])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmpleadoRequest $request, Empleado $empleado): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::transaction(function () use ($empleado, $data) {
                // Actualizar Persona
                $empleado->persona->update([
                    'primer_apellido' => $data['primer_apellido'] ?? $empleado->persona->primer_apellido,
                    'segundo_apellido' => $data['segundo_apellido'] ?? $empleado->persona->segundo_apellido,
                    'nombres'         => $data['nombres'] ?? $empleado->persona->nombres,
                    'ci'              => $data['ci'] ?? $empleado->persona->ci,
                    'ci_expedicion'   => $data['ci_expedicion'] ?? $empleado->persona->ci_expedicion,
                    'fecha_nacimiento'=> $data['fecha_nacimiento'] ?? $empleado->persona->fecha_nacimiento,
                    'genero_id'       => $data['genero_id'] ?? $empleado->persona->genero_id,
                    'estado_civil_id' => $data['estado_civil_id'] ?? $empleado->persona->estado_civil_id,
                    'nacionalidad_id' => $data['nacionalidad_id'] ?? $empleado->persona->nacionalidad_id,
                    'correo_personal' => $data['correo_personal'] ?? $empleado->persona->correo_personal,
                    'celular_personal'=> $data['celular_personal'] ?? $empleado->persona->celular_personal,
                ]);

                // Actualizar Empleado
                $empleado->update([
                    'correo_institucional' => $data['correo_institucional'] ?? $empleado->correo_institucional,
                    'celular_institucional'=> $data['celular_institucional'] ?? $empleado->celular_institucional,
                    'sede_id'              => $data['sede_id'] ?? $empleado->sede_id,
                    'area_trabajo_id'      => $data['area_trabajo_id'] ?? $empleado->area_trabajo_id,
                    'area_dependencia_id'  => $data['area_dependencia_id'] ?? $empleado->area_dependencia_id,
                    'tipo_seguro_id'       => $data['tipo_seguro_id'] ?? $empleado->tipo_seguro_id,
                    'tipo_planilla_id'     => $data['tipo_planilla_id'] ?? $empleado->tipo_planilla_id,
                    'fecha_ingreso'        => $data['fecha_ingreso'] ?? $empleado->fecha_ingreso,
                    'num_seguro_cns'       => $data['num_seguro_cns'] ?? $empleado->num_seguro_cns,
                    'num_seguro_afp'       => $data['num_seguro_afp'] ?? $empleado->num_seguro_afp,
                    'activo'               => $data['activo'] ?? $empleado->activo,
                ]);
            });

            return response()->json([
                'message' => 'Empleado actualizado exitosamente',
                'data' => $empleado->load('persona')
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar empleado', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado): JsonResponse
    {
        try {
            $empleado->delete(); // Soft delete
            return response()->json(['message' => 'Empleado eliminado exitosamente (soft delete)']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar empleado', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Carga Masiva de Personal (Persona + Empleado + Contrato)
     * Recibe un array de objetos con la estructura de la tabla Excel proyectada.
     */
    public function storeMassive(Request $request): JsonResponse
    {
        $request->validate([
            'data' => 'required|array',
            'data.*.nombres' => 'required',
            'data.*.primer_apellido' => 'required',
            'data.*.ci' => 'required',
        ]);

        $results = [
            'created' => 0,
            'errors' => []
        ];

        DB::beginTransaction();
        try {
            foreach ($request->data as $index => $item) {
                try {
                    // 1. Crear Persona
                    $persona = Persona::updateOrCreate(
                        ['ci' => $item['ci']],
                        [
                            'primer_apellido' => strtoupper($item['primer_apellido']),
                            'segundo_apellido' => strtoupper($item['segundo_apellido'] ?? ''),
                            'nombres' => strtoupper($item['nombres']),
                            'ci_expedicion' => $item['ci_expedicion'] ?? null,
                            'fecha_nacimiento' => $item['fecha_nacimiento'] ?? null,
                        ]
                    );

                    // 2. Crear/Actualizar Empleado
                    $empleado = Empleado::updateOrCreate(
                        ['persona_id' => $persona->id],
                        [
                            'fecha_ingreso' => $item['fecha_ingreso'] ?? null,
                            'activo' => 1
                        ]
                    );

                    // 3. Obtener Cargo (o crear si no existe)
                    $cargo_id = null;
                    if (!empty($item['cargo'])) {
                        $cargo = Cargo::firstOrCreate(['nombre' => strtoupper($item['cargo'])]);
                        $cargo_id = $cargo->id;
                    }

                    // 4. Obtener Tipo de Contrato
                    $tipo_contrato_id = null;
                    if (!empty($item['tipo_contrato'])) {
                        $tc = TipoContrato::firstOrCreate(['nombre' => strtoupper($item['tipo_contrato'])]);
                        $tipo_contrato_id = $tc->id;
                    }

                    // 5. Crear Contrato
                    Contrato::updateOrCreate(
                        [
                            'empleado_id' => $empleado->id,
                            'nro_contrato' => $item['nro_contrato'] ?? null,
                            'estado' => 'Vigente'
                        ],
                        [
                            'cargo_id' => $cargo_id,
                            'tipo_contrato_id' => $tipo_contrato_id,
                            'fecha_inicio' => $item['fecha_inicio'] ?? now(),
                            'fecha_fin' => $item['fecha_conclusion'] ?? null,
                            'salario' => (float) ($item['haber_basico'] ?? 0),
                            'bono_frontera' => (float) ($item['bono_frontera'] ?? 0),
                        ]
                    );

                    $results['created']++;
                } catch (Exception $e) {
                    $results['errors'][] = "Fila #$index ({$item['ci']}): " . $e->getMessage();
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Carga masiva completada',
                'summary' => $results
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error en la carga masiva', 'message' => $e->getMessage()], 500);
        }
    }
}
