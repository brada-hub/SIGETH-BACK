<?php

namespace Database\Seeders;

use App\Models\TipoPlanilla;
use Illuminate\Database\Seeder;

class TiposPlanillaSeeder extends Seeder
{
    public function run(): void
    {
        $data = ['Administrativos', 'Docentes', 'Directorio', 'Autoridades', 'Jefes de Carrera'];

        foreach ($data as $nombre) {
            TipoPlanilla::updateOrCreate(['nombre' => $nombre], ['activo' => 1]);
        }
    }
}
