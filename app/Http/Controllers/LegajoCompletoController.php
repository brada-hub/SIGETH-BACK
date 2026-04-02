<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\JsonResponse;

class LegajoCompletoController extends Controller
{
    /**
     * Retorna el Legajo (CV) completo de un empleado en una sola respuesta.
     */
    public function show(Empleado $empleado): JsonResponse
    {
        $empleado->load([
            'persona',
            'bachiller',
            'formaciones',
            'posgrados',
            'docencias',
            'experiencias',
            'capacitaciones',
            'eventos',
            'reconocimientos',
            'producciones',
            'membresias',
            'idiomas.nivelHabla',
            'idiomas.nivelLectura',
            'idiomas.nivelEscritura',
            'contratos.cargo',
            'contratos.tipoContrato',
        ]);

        return response()->json([
            'empleado'               => $empleado,
            'persona'                => $empleado->persona,
            'bachillerato'           => $empleado->bachiller,
            'formacion_academica'    => $empleado->formaciones,
            'posgrado'               => $empleado->posgrados,
            'experiencia_docencia'   => $empleado->docencias,
            'experiencia_profesional'=> $empleado->experiencias,
            'capacitacion'           => $empleado->capacitaciones,
            'eventos'                => $empleado->eventos,
            'reconocimientos'        => $empleado->reconocimientos,
            'produccion_intelectual' => $empleado->producciones,
            'membresias'             => $empleado->membresias,
            'idiomas'                => $empleado->idiomas,
            'nro_hijos'              => $empleado->nroHijos(),
            'contratos_vigentes'     => $empleado->contratos()->where('estado', 'Vigente')->get(),
        ]);
    }
}
