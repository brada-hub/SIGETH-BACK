<?php

namespace Database\Seeders;

use App\Models\NivelAcademico;
use Illuminate\Database\Seeder;

class NivelesAcademicosSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nombre' => 'Técnico Superior', 'orden' => 1],
            ['nombre' => 'Licenciatura', 'orden' => 2],
        ];

        foreach ($data as $item) {
            NivelAcademico::updateOrCreate(['nombre' => $item['nombre']], $item);
        }
    }
}
