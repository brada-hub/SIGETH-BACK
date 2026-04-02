<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Seeder;

class PaisesSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nombre' => 'Bolivia', 'codigo' => 'BOL'],
            ['nombre' => 'Argentina', 'codigo' => 'ARG'],
            ['nombre' => 'Brasil', 'codigo' => 'BRA'],
            ['nombre' => 'Chile', 'codigo' => 'CHL'],
            ['nombre' => 'Perú', 'codigo' => 'PER'],
            ['nombre' => 'Colombia', 'codigo' => 'COL'],
            ['nombre' => 'España', 'codigo' => 'ESP'],
            ['nombre' => 'Estados Unidos', 'codigo' => 'USA'],
        ];

        foreach ($data as $item) {
            Pais::updateOrCreate(['codigo' => $item['codigo']], $item);
        }
    }
}
