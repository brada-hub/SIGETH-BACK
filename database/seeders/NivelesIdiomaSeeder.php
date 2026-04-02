<?php

namespace Database\Seeders;

use App\Models\NivelIdioma;
use Illuminate\Database\Seeder;

class NivelesIdiomaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nombre' => 'B (Básico)', 'orden' => 1],
            ['nombre' => 'R (Regular)', 'orden' => 2],
            ['nombre' => 'N (Nativo/Bien)', 'orden' => 3],
        ];

        foreach ($data as $item) {
            NivelIdioma::updateOrCreate(['nombre' => $item['nombre']], $item);
        }
    }
}
