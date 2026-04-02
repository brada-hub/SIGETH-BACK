<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    /**
     * Listar todas las personas con su contrato vigente y cuenta de usuario.
     */
    public function index(Request $request)
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Persona::with([
            'user',
            'empleado.sede',
        ]);

        // Filtro por búsqueda libre
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function (\Illuminate\Database\Eloquent\Builder $q) use ($s) {
                $q->where('nombres', 'like', "%{$s}%")
                  ->orWhere('primer_apellido', 'like', "%{$s}%")
                  ->orWhere('segundo_apellido', 'like', "%{$s}%")
                  ->orWhere('ci', 'like', "%{$s}%");
            });
        }

        return response()->json($query->orderBy('primer_apellido')->get());
    }

    /**
     * Crear una nueva persona (y opcionalmente su contrato inicial).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombres'          => 'required|string|max:150',
            'primer_apellido'  => 'nullable|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'ci'               => 'required|string|unique:personas,ci',
            'ci_expedicion'    => 'nullable|string|max:10',
            'fecha_nacimiento' => 'nullable|date',
            'genero_id'        => 'nullable|exists:generos,id',
            'correo_personal'  => 'nullable|email|max:150',
            'celular_personal' => 'nullable|string|max:20',
            'direccion'        => 'nullable|string',
            'foto'             => 'nullable|string',
        ]);

        $persona = Persona::create($data);

        return response()->json(
            $persona->load(['user']),
            201
        );
    }

    /**
     * Ver el detalle completo de una persona: historial de contratos, usuario SSO, etc.
     */
    public function show($id)
    {
        $persona = Persona::with([
            'user',
            'empleado.contratos.cargo.tipoPersonal',
            'empleado.sede',
            // --- NUEVO: Cargar Legajo Completo ---
            'empleado.bachiller.tipoColegio',
            'empleado.bachiller.pais',
            'empleado.formaciones.nivel',
            'empleado.formaciones.pais',
            'empleado.posgrados.pais',
            'empleado.docencias',
            'empleado.experiencias',
            'empleado.capacitaciones',
            'empleado.eventos.pais',
            'empleado.membresias',
            'empleado.producciones.pais',
            'empleado.reconocimientos',
            'empleado.idiomas.idiomaCatalogo',
        ])->findOrFail($id);

        // Intentar obtener méritos desde SISPO vinculando por CI (opcional para integración)
        $sispoDb = env('SISPO_DB_DATABASE', 'sispo_db');
        try {
            $meritos = \Illuminate\Support\Facades\DB::table("{$sispoDb}.postulantes as p")
                ->join("{$sispoDb}.postulante_meritos as pm", 'p.id', '=', 'pm.postulante_id')
                ->join("{$sispoDb}.tipo_documentos as td", 'pm.tipo_documento_id', '=', 'td.id')
                ->leftJoin("{$sispoDb}.merito_archivos as ma", 'pm.id', '=', 'ma.merito_id')
                ->where('p.ci', $persona->ci)
                ->select([
                    'pm.id',
                    'td.nombre as tipo_documento',
                    'pm.respuestas',
                    'pm.puntuacion_obtenida',
                    'ma.archivo_path'
                ])
                ->get();

            // Formatear respuestas (son JSON en la BD de SISPO)
            $meritos = $meritos->map(function($m) {
                if (is_string($m->respuestas)) {
                    $m->respuestas = json_decode($m->respuestas, true);
                }
                return $m;
            });

            $persona->meritos_sispo = $meritos;
        } catch (\Exception $e) {
            $persona->meritos_sispo = [];
        }

        return response()->json($persona);
    }

    /**
     * Actualizar datos personales de una persona.
     */
    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        $data = $request->validate([
            'nombres'          => 'sometimes|required|string|max:150',
            'primer_apellido'  => 'nullable|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'ci'               => ['nullable', Rule::unique('personas', 'ci')->ignore($id)],
            'ci_expedicion'    => 'nullable|string|max:10',
            'fecha_nacimiento' => 'nullable|date',
            'genero_id'        => 'nullable|exists:generos,id',
            'correo_personal'  => 'nullable|email|max:150',
            'celular_personal' => 'nullable|string|max:20',
            'direccion'        => 'nullable|string',
            'foto'             => 'nullable|string',
        ]);

        // Aplicar Uppercase a campos de texto
        if (isset($data['nombres'])) $data['nombres'] = strtoupper($data['nombres']);
        if (isset($data['primer_apellido'])) $data['primer_apellido'] = strtoupper($data['primer_apellido']);
        if (isset($data['segundo_apellido'])) $data['segundo_apellido'] = strtoupper($data['segundo_apellido']);

        $persona->update($data);

        return response()->json(
            $persona->load(['user'])
        );
    }

    /**
     * Soft-delete: la persona no se borra físicamente para conservar historial.
     */
    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);
        $persona->delete();
        return response()->json(['message' => 'Persona desactivada correctamente']);
    }

    /**
     * Buscar persona por CI — útil para autocompletar en SISPO al postular.
     */
    public function buscarPorCi(Request $request)
    {
        $request->validate(['ci' => 'required|string']);

        $persona = Persona::with([
            'user',
            'empleado.sede',
        ])->where('ci', $request->ci)->first();

        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }

        return response()->json($persona);
    }

    /**
     * Obtener "Mi Hoja de Vida" (Datos de la cuenta autenticada).
     */
    public function miHojaVida(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        // Si no tiene persona vinculada, intentar vincular por CI
        if (!$user->persona_id && $user->ci) {
            $personaExistente = Persona::where('ci', $user->ci)->first();
            if ($personaExistente) {
                $user->persona_id = $personaExistente->id;
                $user->save();
            }
        }

        if (!$user->persona_id) {
            // Si después de todo no hay persona, podemos devolver un objeto temporal basado en el usuario
            // para que la interfaz no se rompa (404), o permitir que el front sepa que debe crearla.
            return response()->json([
                'id' => null,
                'nombres' => $user->nombres,
                'primer_apellido' => $user->primer_apellido,
                'segundo_apellido' => $user->segundo_apellido,
                'ci' => $user->ci,
                'ci_expedicion' => $user->ci_expedicion, // Si existe en User
                'correo_personal' => $user->email,
                'celular' => $user->phone,
                'foto' => $user->avatar,
                'is_virtual' => true // Indicador para el front
            ]);
        }

        $persona = Persona::with(['contratoVigente.cargo.tipoPersonal', 'contratoVigente.sede'])
                          ->find($user->persona_id);

        return response()->json($persona);
    }

    /**
     * Actualizar "Mi Hoja de Vida" (Para empelados autenticados).
     * Permite subir CV y Foto, y editar datos básicos.
     */
    public function actualizarMiHojaVida(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->persona_id) {
             return response()->json(['message' => 'El usuario no tiene una hoja de vida vinculada'], 404);
        }

        $persona = Persona::findOrFail($user->persona_id);

        $request->validate([
            'nombres'          => 'nullable|string|max:150',
            'celular'          => 'nullable|string|max:20',
            'direccion'        => 'nullable|string',
            'correo_personal'  => 'nullable|email',
            'foto'             => 'nullable|image|max:2048',
            'cv_pdf'           => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->has('nombres')) $persona->nombres = $request->nombres;
        if ($request->has('celular_personal')) $persona->celular_personal = $request->celular_personal;
        if ($request->has('direccion')) $persona->direccion = $request->direccion;
        if ($request->has('correo_personal')) $persona->correo_personal = $request->correo_personal;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('personas/fotos', 'public');
            $persona->foto = $path;
        }

        if ($request->hasFile('cv_pdf')) {
            $path = $request->file('cv_pdf')->store('personas/cv', 'public');
            $persona->cv_path = $path;
        }

        $persona->save();

        return response()->json([
            'success' => true,
            'message' => 'Hoja de vida actualizada correctamente.',
            'persona' => $persona
        ]);
    }

    /**
     * Registro Directo Público (Formulario oculto para staff inicial o personal invitado).
     */
    /**
     * Verifica identidad por CI y Fecha de Nacimiento para acceso seguro (2-pasos)
     */
    public function verificarSeguridad(Request $request)
    {
        $request->validate([
            'ci' => 'required',
            'fecha_nacimiento' => 'required|date'
        ]);

        $persona = Persona::where('ci', $request->ci)
                          ->where('fecha_nacimiento', $request->fecha_nacimiento)
                          ->first();

        if (!$persona) {
            return response()->json([
                'message' => 'Los datos proporcionados no coinciden con nuestros registros.'
            ], 403);
        }

        // Si existe, aseguramos que tenga Empleado y Usuario (para permitir actualizaciones con token)
        $empleado = \App\Models\Empleado::firstOrCreate(
            ['persona_id' => $persona->id],
            ['activo' => 1]
        );

        $user = $empleado->user;
        if (!$user) {
            $user = \App\Models\User::create([
                'username' => 'PUB_' . $persona->ci . '_' . rand(100, 999),
                'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                'empleado_id' => $empleado->id,
                'activo' => 1
            ]);
        }

        $token = $user->createToken('public-update-token')->plainTextToken;

        return response()->json([
            'persona' => $persona->load('empleado'),
            'token' => $token,
            'isNew' => false
        ]);
    }

    public function registrarDirecto(Request $request)
    {
        $setting = \App\Models\Setting::where('key', 'registro_directo_enabled')->first();
        if ($setting && $setting->value === 'false') {
            return response()->json(['message' => 'El enlace de registro público se encuentra deshabilitado por un administrador.'], 403);
        }

        $request->validate([
            'nombres'          => 'required|string|max:150',
            'primer_apellido'  => 'required|string|max:100',
            'ci'               => 'required|string',
            'correo_personal'  => 'required|email|max:150',
        ]);

        return DB::transaction(function () use ($request) {
            $data = $request->only([
                'nombres', 'primer_apellido', 'segundo_apellido', 
                'ci', 'ci_expedicion', 'fecha_nacimiento', 'genero_id',
                'estado_civil_id', 'nacionalidad_id',
                'departamento_residencia', 'ciudad_residencia', 'direccion',
                'celular_personal', 'correo_personal', 'resumen'
            ]);

            if ($request->hasFile('foto_file')) {
                $data['foto'] = $request->file('foto_file')->store('personas/fotos', 'public');
            }

            // Aplicar Uppercase a campos de texto
            $data['nombres'] = strtoupper($data['nombres']);
            $data['primer_apellido'] = strtoupper($data['primer_apellido']);
            if (isset($data['segundo_apellido'])) $data['segundo_apellido'] = strtoupper($data['segundo_apellido']);

            // Update or Create Persona
            $persona = Persona::updateOrCreate(
                ['ci' => $request->ci],
                $data
            );

            // Asegurar Empleado y Usuario para permitir carga de Legajo inmediata con Token
            $empleado = \App\Models\Empleado::firstOrCreate(
                ['persona_id' => $persona->id],
                ['activo' => 1]
            );

            $user = $empleado->user;
            if (!$user) {
                $user = \App\Models\User::create([
                    'username' => 'GUEST_' . $persona->ci . '_' . rand(100, 999),
                    'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                    'empleado_id' => $empleado->id,
                    'activo' => 1
                ]);
            }

            $token = $user->createToken('guest-registration-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => '¡Datos personales registrados exitosamente!',
                'persona' => $persona->load('empleado'),
                'token' => $token,
                'isNew' => true
            ], 201);
        });
    }
}
