<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Departamento;

class CatalogosController extends Controller
{
    public function tiposColegio(): JsonResponse
    {
        $tipos = DB::table('tipos_colegio')->select('id', 'nombre')->get();
        return response()->json($tipos);
    }

    public function paises(): JsonResponse
    {
        $paises = DB::table('paises')->select('id', 'nombre')->get();
        return response()->json($paises);
    }

    public function nivelesAcademicos(): JsonResponse
    {
        $niveles = DB::table('niveles_academicos')->select('id', 'nombre')->get();
        return response()->json($niveles);
    }

    public function tiposPosgrado(): JsonResponse
    {
        $tipos = DB::table('tipos_posgrado')->select('id', 'nombre')->get();
        return response()->json($tipos);
    }

    public function generos(): JsonResponse
    {
        $items = DB::table('generos')->select('id', 'nombre')->get();
        return response()->json($items);
    }

    public function estadosCiviles(): JsonResponse
    {
        $items = DB::table('estados_civiles')->select('id', 'nombre')->get();
        return response()->json($items);
    }

    public function tiposEvento(): JsonResponse
    {
        $items = DB::table('tipos_evento')->select('id', 'nombre')->get();
        return response()->json($items);
    }

    public function tiposParticipacionEvento(): JsonResponse
    {
        $items = DB::table('tipos_participacion_evento')->select('id', 'nombre')->get();
        return response()->json($items);
    }

    public function tiposReconocimiento(): JsonResponse
    {
        $items = DB::table('tipos_reconocimiento')->select('id', 'nombre')->get();
        return response()->json($items);
    }

    public function tiposProduccionIntelectual(): JsonResponse
    {
        $items = DB::table('tipos_produccion_intelectual')->select('id', 'nombre')->get();
        return response()->json($items);
    }

    public function tiposMembresia(): JsonResponse
    {
        $items = DB::table('tipos_membresia')->select('id', 'nombre')->get();
        return response()->json($items);
    }

    public function nivelesIdioma(): JsonResponse
    {
        $items = DB::table('niveles_idioma')->select('id', 'nombre')->get();
        return response()->json($items);
    }

    public function idiomas(): JsonResponse
    {
        $items = DB::table('idiomas')->select('id', 'nombre')->orderBy('nombre')->get();
        return response()->json($items);
    }

    public function cargos(): JsonResponse
    {
        $items = DB::table('cargos')->select('id', 'nombre')->where('activo', 1)->orderBy('nombre')->get();
        return response()->json($items);
    }

    public function tiposContrato(): JsonResponse
    {
        $items = DB::table('tipos_contrato')->select('id', 'nombre')->where('activo', 1)->orderBy('nombre')->get();
        return response()->json($items);
    }

    public function departamentos(): JsonResponse
    {
        $deps = Departamento::where('activo', 1)->select('id', 'nombre')->get();
        return response()->json($deps);
    }
}
