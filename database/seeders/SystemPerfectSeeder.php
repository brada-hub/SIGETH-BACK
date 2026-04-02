<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sistema;
use App\Models\Permiso;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SystemPerfectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "=== Iniciando Seeder Master de Permisos ===\n";

        // 1. Limpiar para empezar de cero (OJO: Esto borra permisos y roles para empezar limpio)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permisos')->truncate();
        DB::table('rol_permisos')->truncate();
        DB::table('roles')->truncate(); // Vamos a borrar roles para recargar solo los 3 deseados
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Obtener los Sistemas
        $sigeth = Sistema::where('slug', 'sigeth')->first();
        $sispo = Sistema::where('slug', 'sispo')->first();
        $sigva = Sistema::where('slug', 'sigva')->first();
        $sso = Sistema::where('slug', 'sso')->first();

        if (!$sigeth || !$sispo || !$sigva) {
            echo "Error: Algunos sistemas no existen. Ejecuta AdminUserSeeder primero.\n";
            return;
        }

        // 3. Crear los 3 Roles Globales
        echo " - Creando roles globales (Administrador, Talento Humano, Usuarios)...\n";
        $adminRol = Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Acceso total al sistema',
            'activo' => 1
        ]);
        
        $thRol = Rol::create([
            'nombre' => 'Talento Humano',
            'descripcion' => 'Gestión operativa de personal y recursos humanos',
            'activo' => 1
        ]);
        
        $userRol = Rol::create([
            'nombre' => 'Usuarios',
            'descripcion' => 'Acceso básico para consulta personal de legajo y trámites',
            'activo' => 1
        ]);

        // 4. Crear Permisos para SISPO (slug: sispo)
        echo " - Creando permisos para SISPO...\n";
        $sispoPerms = [
            'calendario', 'documentacion', 'empleados', 'feriados', 'reportes', 'solicitudes', 'vacaciones_dashboard'
        ];
        foreach ($sispoPerms as $p) {
            Permiso::create([
                'nombre' => $p,
                'descripcion' => 'Permiso para ' . $p,
                'modulo' => 'SISPO',
                'sistema_id' => $sispo->id
            ]);
        }

        // 5. Crear Permisos para SIGVA (slug: sigva)
        echo " - Creando permisos para SIGVA...\n";
        $sigvaPerms = [
            'cargos', 'convocatorias', 'dashboard', 'evaluaciones', 'postulaciones', 'requisitos', 'roles', 'sedes', 'usuarios', 'ver_mi_legajo', 'ver_todo_personal'
        ];
        foreach ($sigvaPerms as $p) {
            Permiso::create([
                'nombre' => $p,
                'descripcion' => 'Permiso para ' . $p,
                'modulo' => 'SIGVA',
                'sistema_id' => $sigva->id
            ]);
        }

        // 6. Crear Permisos para SIGETH (slug: sigeth)
        echo " - Creando permisos para SIGETH...\n";
        $sigethPerms = [
            'gestionar_aplicaciones', 'gestionar_roles', 'gestionar_sedes', 'gestionar_usuarios'
        ];
        foreach ($sigethPerms as $p) {
            Permiso::create([
                'nombre' => $p,
                'descripcion' => 'Permiso para ' . $p,
                'modulo' => 'SIGETH',
                'sistema_id' => $sigeth->id
            ]);
        }

        // 7. Asignar Permisos a los Roles
        $allPermIds = Permiso::all()->pluck('id')->toArray();
        
        // ADMINISTRADOR: Tiene TODOS los permisos de todos los sistemas
        echo " - Asignando todos los permisos al rol ADMINISTRADOR...\n";
        $adminRol->permisos()->sync($allPermIds);
        
        // TALENTO HUMANO: Tiene permisos operativos (por ejemplo SIGETH casi todo, SISPO básico)
        echo " - Asignando permisos operativos al rol TALENTO HUMANO...\n";
        $thPerms = Permiso::where('modulo', 'SIGETH')->orWhereIn('nombre', ['empleados', 'reportes'])->pluck('id')->toArray();
        $thRol->permisos()->sync($thPerms);
        
        // USUARIOS: Solo puede ver su propio legajo
        echo " - Asignando permisos básicos al rol USUARIOS...\n";
        $userPerms = Permiso::where('nombre', 'ver_mi_legajo')->pluck('id')->toArray();
        $userRol->permisos()->sync($userPerms);

        // 8. Re-vincular al usuario admin al nuevo Administrador global (ya que truncamos roles)
        $adminUser = User::where('username', 'admin')->first();
        if ($adminUser) {
            $adminUser->update(['rol_id' => $adminRol->id]);
            // Actualizar su rol en cada sistema en user_roles
            DB::table('user_roles')->where('user_id', $adminUser->id)->update(['rol_id' => $adminRol->id]);
        }

        echo "=== SEEDER COMPLETADO CON ÉXITO ===\n";
    }
}
