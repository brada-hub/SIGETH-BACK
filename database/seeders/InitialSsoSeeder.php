<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialSsoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Sedes
        $sedeLP = \App\Models\Sede::firstOrCreate(['nombre' => 'La Paz - Central']);
        $sedeCB = \App\Models\Sede::firstOrCreate(['nombre' => 'Cochabamba']);

        // 2. Aplicaciones
        $sigva = \App\Models\Application::updateOrCreate(
            ['key' => 'sigva'],
            [
                'nombre' => 'SIGVA',
                'url' => 'https://sigva.xpertiaplus.com',
                'icono' => 'assignment_ind',
                'color' => 'purple',
                'descripcion' => 'Sistema de Gestión de Vacaciones y Ausencias.'
            ]
        );

        $sispo = \App\Models\Application::updateOrCreate(
            ['key' => 'sispo'],
            [
                'nombre' => 'SISPO',
                'url' => 'https://sipost.xpertiaplus.com',
                'icono' => 'groups',
                'color' => 'teal',
                'descripcion' => 'Sistema de Gestión de Personal y Ocupacional.'
            ]
        );

        $sigeth = \App\Models\Application::updateOrCreate(
            ['key' => 'sigeth'],
            [
                'nombre' => 'SIGETH',
                'url' => 'https://sigeth.xpertiaplus.com',
                'icono' => 'shield',
                'color' => 'deep-purple',
                'descripcion' => 'Panel de Control Principal SSO.'
            ]
        );

        // 3. Usuario Admin de Prueba
        $admin = \App\Models\User::firstOrCreate(
            ['ci' => '1234567'],
            [
                'nombres' => 'Admin',
                'apellido_paterno' => 'SSO',
                'email' => 'admin@sso.com',
                'password' => \Hash::make('admin123'),
                'sede_id' => $sedeLP->id,
                'activo' => true,
                'must_change_password' => false
            ]
        );

        // 4. Asignar accesos
        if ($admin->applications()->count() === 0) {
            $admin->applications()->attach($sigva->id, ['role' => 'admin', 'permissions' => json_encode(['all'])]);
            $admin->applications()->attach($sispo->id, ['role' => 'admin', 'permissions' => json_encode(['all'])]);
        }
    }
}
