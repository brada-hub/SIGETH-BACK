<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\User;
use App\Models\Sistema;
use App\Models\Rol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Crear Persona base
            $persona = Persona::updateOrCreate(
                ['ci' => '13260003'],
                [
                    'nombres' => 'Administrador',
                    'primer_apellido' => 'Sistema',
                    'segundo_apellido' => '',
                    'ci_expedicion' => 'LP',
                    'correo_personal' => 'admin@sistema.com'
                ]
            );

            // 2. Asegurar que existan los 4 sistemas principales si no existen
            $sistemasData = [
                ['slug' => 'sso', 'nombre' => 'SSO - Central', 'url' => config('app.url'), 'icono' => 'security', 'color' => 'blue'],
                ['slug' => 'sigeth', 'nombre' => 'SIGETH - RRHH', 'url' => env('SIGETH_URL', 'http://localhost:9000'), 'icono' => 'people', 'color' => 'indigo'],
                ['slug' => 'sispo', 'nombre' => 'SISPO - Postulaciones', 'url' => env('SISPO_URL', 'http://localhost:9001'), 'icono' => 'assignment', 'color' => 'purple'],
                ['slug' => 'sigva', 'nombre' => 'SIGVA - Vacaciones', 'url' => env('SIGVA_URL', 'http://localhost:9002'), 'icono' => 'event', 'color' => 'teal'],
            ];

            foreach ($sistemasData as $data) {
                Sistema::updateOrCreate(['slug' => $data['slug']], array_merge($data, ['activo' => 1]));
            }

            // 3. Crear Roles Globales (sin sistema_id específico o asignados al SSO)
            $adminRol = Rol::updateOrCreate(
                ['nombre' => 'Administrador'],
                ['descripcion' => 'Acceso total al sistema', 'sistema_id' => null, 'activo' => 1]
            );
            
            Rol::updateOrCreate(
                ['nombre' => 'Talento Humano'],
                ['descripcion' => 'Gestión de personal y recursos humanos', 'sistema_id' => null, 'activo' => 1]
            );
            
            Rol::updateOrCreate(
                ['nombre' => 'Usuarios'],
                ['descripcion' => 'Acceso básico de funcionario', 'sistema_id' => null, 'activo' => 1]
            );

            // 4. Crear Usuario vinculado a Persona y al Rol Administrador Global
            $user = User::updateOrCreate(
                ['username' => 'admin'],
                [
                    'persona_id' => $persona->id,
                    'ci' => $persona->ci,
                    'nombres' => $persona->nombres,
                    'apellido_paterno' => $persona->primer_apellido,
                    'apellido_materno' => $persona->segundo_apellido,
                    'apellidos' => $persona->primer_apellido . ' ' . $persona->segundo_apellido,
                    'email' => 'admin@sistema.com',
                    'password' => Hash::make('Admin123!'),
                    'rol_id' => $adminRol->id,
                    'activo' => 1,
                ]
            );

            // 5. Vincular al usuario con todos los sistemas con el mismo rol Administrador
            $sistemas = Sistema::all();
            foreach ($sistemas as $sistema) {
                DB::table('user_roles')->updateOrInsert(
                    ['user_id' => $user->id, 'sistema_id' => $sistema->id],
                    ['rol_id' => $adminRol->id, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        });
    }
}
