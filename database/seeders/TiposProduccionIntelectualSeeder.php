<?php

namespace Database\Seeders;

use App\Models\TipoProduccionIntelectual;
use Illuminate\Database\Seeder;

class TiposProduccionIntelectualSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Artículo científico', 'Libro', 'Capítulo de libro', 
            'Ponencia en congreso', 'Tesis dirigida', 'Patente', 'Informe técnico'
        ];

        foreach ($data as $nombre) {
            TipoProduccionIntelectual::updateOrCreate(['nombre' => $nombre]);
        }
    }
}
