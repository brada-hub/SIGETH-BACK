<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoDocumento;
use App\Models\PersonaDocumento;
use App\Models\DocumentoArchivo;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    /**
     * Listar todos los tipos de documentos disponibles.
     */
    public function tiposDocumento()
    {
        return response()->json(TipoDocumento::orderBy('orden')->get());
    }

    /**
     * Guardar una solicitud de documentos (o actualización) para una persona.
     */
    public function guardarDocumentos(Request $request)
    {
        $user = auth()->user();
        $personaId = $request->input('persona_id');

        // Si no se envía un ID válido, intentamos usar el del usuario o crear uno
        if (!$personaId || $personaId === 'null') {
            if ($user->persona_id) {
                $personaId = $user->persona_id;
            } else {
                // Crear la entidad Persona automáticamente desde el Usuario
                $persona = Persona::create([
                    'nombres'          => $user->nombres,
                    'apellido_paterno' => $user->apellido_paterno,
                    'apellido_materno' => $user->apellido_materno,
                    'ci'               => $user->ci,
                    'correo_personal'  => $user->email,
                    'celular'          => $user->phone,
                ]);
                $personaId = $persona->id;
                $user->update(['persona_id' => $personaId]);
            }
        }

        $request->validate([
            'documentos' => 'required|array',
            'documentos.*.tipo_documento_id' => 'required|exists:tipos_documento,id',
        ]);

        return DB::transaction(function () use ($personaId, $request) {
            $documentosData = $request->input('documentos', []);

            foreach ($documentosData as $index => $docData) {
                $tipoId = $docData['tipo_documento_id'];
                
                // Si ya existe un registro para este tipo y persona, y no permite múltiples, lo actualizamos.
                // Pero según SISPO, solemos crear nuevos registros o actualizar por ID si viene.
                
                $personaDocumento = PersonaDocumento::updateOrCreate(
                    [
                        'id' => $docData['id'] ?? null,
                        'persona_id' => $personaId,
                        'tipo_documento_id' => $tipoId,
                    ],
                    [
                        'respuestas' => is_string($docData['respuestas']) ? json_decode($docData['respuestas'], true) : ($docData['respuestas'] ?? []),
                        'estado_verificacion' => 'pendiente',
                    ]
                );

                // Manejo de Archivos
                $files = $request->file("documentos.{$index}.archivos");
                if ($files && is_array($files)) {
                    foreach ($files as $configId => $file) {
                        if ($file instanceof \Illuminate\Http\UploadedFile) {
                            $path = $file->store("personas/{$personaId}/documentos", 'public');
                            
                            DocumentoArchivo::updateOrCreate(
                                [
                                    'documento_id' => $personaDocumento->id,
                                    'config_archivo_id' => $configId
                                ],
                                ['archivo_path' => $path]
                            );
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Documentación guardada correctamente en el expediente central.'
            ]);
        });
    }

    /**
     * Obtener los documentos ya cargados de una persona.
     */
    public function listPorPersona($personaId)
    {
        $documentos = PersonaDocumento::with(['tipoDocumento', 'archivos'])
            ->where('persona_id', $personaId)
            ->get();

        return response()->json($documentos);
    }
}
