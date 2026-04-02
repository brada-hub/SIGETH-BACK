<?php

namespace Database\Seeders;

use App\Models\PosicionInstitucional;
use Illuminate\Database\Seeder;

class PosicionesInstitucionalesSeeder extends Seeder
{
    public function run(): void
    {
        $data = ['Administrativo', 'Autoridad', 'Consultor en Línea', 'Docente', 'Técnico', 'Directorio'];

        foreach ($data as $nombre) {
            PosicionInstitucional::updateOrCreate(['nombre' => $nombre], ['activo' => 1]);
        }
    }
}
