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
        $sedeLP = \App\Models\Sede::create([
            'nombre' => 'La Paz - Central',
            'ciudad' => 'La Paz',
            'direccion' => 'Av. Arce #123'
        ]);

        $sedeCB = \App\Models\Sede::create([
            'nombre' => 'Cochabamba',
            'ciudad' => 'Cochabamba',
            'direccion' => 'Calle Jordan #456'
        ]);

        // 2. Aplicaciones
        $sigva = \App\Models\Application::create([
            'nombre' => 'SIGVA',
            'url' => 'http://localhost:9000', // Donde corra SIGVA Front
            'icono' => 'assignment_ind',
            'color' => 'purple',
            'descripcion' => 'Sistema de Gestión de Vacaciones y Ausencias.'
        ]);

        $sispo = \App\Models\Application::create([
            'nombre' => 'SISPO',
            'url' => 'http://localhost:9001', // Donde corra SISPO Front
            'icono' => 'groups',
            'color' => 'teal',
            'descripcion' => 'Sistema de Gestión de Personal y Ocupacional.'
        ]);

        // 3. Usuario Admin de Prueba
        $admin = \App\Models\User::create([
            'ci' => '1234567',
            'nombres' => 'Admin',
            'apellidos' => 'SSO',
            'email' => 'admin@sso.com',
            'password' => \Hash::make('admin123'),
            'sede_id' => $sedeLP->id,
            'phone' => '77712345'
        ]);

        // 4. Asignar accesos
        $admin->applications()->attach($sigva->id, ['role' => 'admin', 'permissions' => json_encode(['all'])]);
        $admin->applications()->attach($sispo->id, ['role' => 'admin', 'permissions' => json_encode(['all'])]);
    }
}
