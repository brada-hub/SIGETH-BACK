<?php

namespace App\Http\Controllers;

use App\Models\LegajoArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Exception;

class LegajoArchivoController extends Controller
{
    /**
     * Sube un archivo al legajo.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'legajo_tabla' => 'required|string',
            'legajo_id' => 'required|integer',
            'tipo_archivo' => 'required|string',
            'archivo' => 'required|file|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('archivo');
            $path = $file->store('legajos/' . $request->legajo_tabla, 'private');

            $archivo = LegajoArchivo::create([
                'legajo_tabla' => $request->legajo_tabla,
                'legajo_id' => $request->legajo_id,
                'tipo_archivo' => $request->tipo_archivo,
                'archivo_path' => $path,
                'nombre_original' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'tamano_bytes' => $file->getSize(),
            ]);

            return response()->json(['message' => 'Archivo subido correctamente', 'data' => $archivo], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al subir archivo', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Descarga un archivo.
     */
    public function show(LegajoArchivo $archivo)
    {
        if (!Storage::disk('private')->exists($archivo->archivo_path)) {
            return response()->json(['error' => 'Archivo físico no encontrado'], 404);
        }

        return Storage::disk('private')->download($archivo->archivo_path, $archivo->nombre_original);
    }

    /**
     * Elimina un archivo.
     */
    public function destroy(LegajoArchivo $archivo): JsonResponse
    {
        try {
            // Eliminar archivo físico
            if (Storage::disk('private')->exists($archivo->archivo_path)) {
                Storage::disk('private')->delete($archivo->archivo_path);
            }

            // Eliminar registro DB
            $archivo->delete();

            return response()->json(['message' => 'Archivo eliminado correctamente']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar archivo', 'message' => $e->getMessage()], 500);
        }
    }
}
