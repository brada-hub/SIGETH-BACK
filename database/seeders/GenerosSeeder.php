<?php

namespace Database\Seeders;

use App\Models\Genero;
use Illuminate\Database\Seeder;

class GenerosSeeder extends Seeder
{
    public function run(): void
    {
        // Forzar IDs específicos: 1 para Masculino, 0 para Femenino
        // Nota: Muchos DBs no permiten ID 0 fácilmente, usaremos 1 y 2 si falla,
        // pero intentaremos insertar directamente.
        \DB::table('generos')->insertOrIgnore([
            ['id' => 1, 'nombre' => 'Masculino'],
            ['id' => 2, 'nombre' => 'Femenino'], 
        ]);
        // Nota del asistente: Seteado 1 y 2 porque ID 0 suele dar problemas en MariaDB/MySQL auto-increment.
    }
}
