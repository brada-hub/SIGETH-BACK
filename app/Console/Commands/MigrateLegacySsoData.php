<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Sede;
use App\Models\Application;

class MigrateLegacySsoData extends Command
{
    protected $signature = 'sso:migrate-all';
    protected $description = 'Migrar datos desde los backups de SIGVA y SIPO a sso_db';

    public function handle()
    {
        $this->info('🚀 Iniciando Gran Migración de Backups...');

        // 1. Asegurar Aplicaciones
        $this->info('⚙️ Configurando Aplicaciones (SIGVA y SISPO)...');
        $sigvaApp = Application::updateOrCreate(
            ['nombre' => 'SIGVA'],
            [
                'url' => 'http://localhost:5173',
                'icono' => 'assignment_ind',
                'descripcion' => 'Sistema de Gestión de Vacaciones y Ausencias',
                'activo' => true,
                'color' => 'purple'
            ]
        );

        $sispoApp = Application::updateOrCreate(
            ['nombre' => 'SISPO'],
            [
                'url' => 'http://localhost:8080',
                'icono' => 'groups',
                'descripcion' => 'Sistema de Gestión de Personal y Ocupacional',
                'activo' => true,
                'color' => 'teal'
            ]
        );

        // 2. Migrar Sedes (Prioridad SIPO por estar más completo)
        $this->info('📍 Migrando Sedes...');
        $sedesSipo = DB::connection('legacy_sipo')->table('sedes')->get();
        foreach ($sedesSipo as $s) {
            Sede::updateOrCreate(
                ['id' => $s->id],
                [
                    'nombre' => $s->nombre,
                    'direccion' => $s->direccion ?? 'Sin dirección',
                    'ciudad' => $s->departamento ?? 'Bolivia',
                ]
            );
        }

        $sedesSigva = DB::connection('legacy_sigva')->table('sedes')->get();
        foreach ($sedesSigva as $s) {
            Sede::updateOrCreate(
                ['nombre' => $s->nombre],
                [
                    'direccion' => $s->direccion ?? 'Sin dirección',
                    'ciudad' => 'Bolivia',
                ]
            );
        }

        // 3. Migrar Usuarios desde SISPO
        $this->info('👥 Migrando Usuarios desde SISPO...');
        $usersSipo = DB::connection('legacy_sipo')->table('users')->get();
        foreach ($usersSipo as $u) {
            if (!$u->ci) continue; // Ignorar si no tiene CI

            $user = User::updateOrCreate(
                ['ci' => $u->ci],
                [
                    'nombres' => $u->nombres ?? 'Usuario',
                    'apellidos' => $u->apellidos ?? 'Sin Apellido',
                    'email' => $u->email ?? ($u->ci . '@sso.com'),
                    'password' => $u->password,
                    'sede_id' => $u->sede_id,
                    'phone' => '00000000',
                    'avatar' => $u->avatar ?? null,
                ]
            );

            // Asignar SISPO por defecto si estaba en SIPO
            $user->applications()->syncWithoutDetaching([$sispoApp->id => [
                'role' => 'admin',
                'permissions' => json_encode(['all'])
            ]]);
        }

        // 4. Migrar Usuarios desde SIGVA (y mappear si ya existen)
        $this->info('👥 Migrando/Consolidando Usuarios desde SIGVA...');
        $usersSigva = DB::connection('legacy_sigva')->table('users')->get();
        foreach ($usersSigva as $u) {
            if (!$u->ci) continue;

            $apellidos = trim(($u->apellido_paterno ?? '') . ' ' . ($u->apellido_materno ?? ''));
            if (empty($apellidos)) $apellidos = 'Apellido';

            $user = User::updateOrCreate(
                ['ci' => $u->ci],
                [
                    'nombres' => $u->name ?? $u->nombres ?? 'Usuario',
                    'apellidos' => !empty($apellidos) ? $apellidos : 'Sin Apellido',
                    'email' => !empty($u->email) ? $u->email : ($u->ci . '@sigva.com'),
                    'password' => $u->password,
                    'phone' => '00000000',
                ]
            );

            // Asignar SIGVA
            $user->applications()->syncWithoutDetaching([$sigvaApp->id => [
                'role' => 'admin',
                'permissions' => json_encode(['all'])
            ]]);
        }

        $count = User::count();
        $this->info("✅ ¡Migración completada! Se han consolidado $count usuarios en el SSO.");
        return 0;
    }
}
