<?php

namespace Database\Seeders;

use App\Models\TipoPosgrado;
use Illuminate\Database\Seeder;

class TiposPosgradoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nombre' => 'Diplomado', 'orden' => 1],
            ['nombre' => 'Especialidad', 'orden' => 2],
            ['nombre' => 'Maestría', 'orden' => 3],
            ['nombre' => 'Doctorado', 'orden' => 4],
            ['nombre' => 'Postdoctorado', 'orden' => 5],
        ];

        foreach ($data as $item) {
            TipoPosgrado::updateOrCreate(['nombre' => $item['nombre']], $item);
        }
    }
}
