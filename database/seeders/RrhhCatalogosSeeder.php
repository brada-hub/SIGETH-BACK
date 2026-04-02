<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RrhhCatalogosSeeder extends Seeder
{
    /**
     * Populate los catálogos base del módulo RRHH:
     * - tipos_personal
     * - cargos (ejemplos comunes)
     */
    public function run(): void
    {
        // --------------------------------------------------------
        // 1. TIPOS DE PERSONAL
        // --------------------------------------------------------
        $tipos = [
            ['nombre' => 'Administrativo',       'descripcion' => 'Personal administrativo de planta'],
            ['nombre' => 'Consultor en Línea',   'descripcion' => 'Consultores contratados por proyecto'],
            ['nombre' => 'Personal de Apoyo',    'descripcion' => 'Personal de apoyo y servicios generales'],
            ['nombre' => 'Técnico',              'descripcion' => 'Personal técnico especializado'],
            ['nombre' => 'Directivo',            'descripcion' => 'Directores y jefes de área'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos_personal')->insertOrIgnore([
                'nombre'      => $tipo['nombre'],
                'descripcion' => $tipo['descripcion'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // --------------------------------------------------------
        // 2. CARGOS BASE (referenciando tipos insertados arriba)
        // --------------------------------------------------------
        $administrativo   = DB::table('tipos_personal')->where('nombre', 'Administrativo')->value('id');
        $consultor        = DB::table('tipos_personal')->where('nombre', 'Consultor en Línea')->value('id');
        $apoyo            = DB::table('tipos_personal')->where('nombre', 'Personal de Apoyo')->value('id');
        $tecnico          = DB::table('tipos_personal')->where('nombre', 'Técnico')->value('id');
        $directivo        = DB::table('tipos_personal')->where('nombre', 'Directivo')->value('id');

        $cargos = [
            ['nombre_cargo' => 'Director General',              'tipo_personal_id' => $directivo],
            ['nombre_cargo' => 'Jefe de Recursos Humanos',     'tipo_personal_id' => $directivo],
            ['nombre_cargo' => 'Jefe de Sistemas',             'tipo_personal_id' => $directivo],
            ['nombre_cargo' => 'Responsable Administrativo',   'tipo_personal_id' => $administrativo],
            ['nombre_cargo' => 'Asistente Administrativo',     'tipo_personal_id' => $administrativo],
            ['nombre_cargo' => 'Consultor Senior',             'tipo_personal_id' => $consultor],
            ['nombre_cargo' => 'Consultor Junior',             'tipo_personal_id' => $consultor],
            ['nombre_cargo' => 'Desarrollador de Software',    'tipo_personal_id' => $tecnico],
            ['nombre_cargo' => 'Técnico de Soporte',           'tipo_personal_id' => $tecnico],
            ['nombre_cargo' => 'Mensajero',                    'tipo_personal_id' => $apoyo],
            ['nombre_cargo' => 'Conserje',                     'tipo_personal_id' => $apoyo],
            ['nombre_cargo' => 'Recepcionista',                'tipo_personal_id' => $apoyo],
        ];

        foreach ($cargos as $cargo) {
            DB::table('cargos')->insertOrIgnore([
                'nombre'           => $cargo['nombre_cargo'],
                'tipo_personal_id' => $cargo['tipo_personal_id'],
                'activo'           => true,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }
}
