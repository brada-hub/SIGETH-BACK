<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IdiomasSeeder extends Seeder
{
    public function run(): void
    {
        $idiomas = [
            ['nombre' => 'ESPAÑOL', 'codigo' => 'es'],
            ['nombre' => 'INGLÉS', 'codigo' => 'en'],
            ['nombre' => 'PORTUGUÉS', 'codigo' => 'pt'],
            ['nombre' => 'FRANCÉS', 'codigo' => 'fr'],
            ['nombre' => 'ALEMÁN', 'codigo' => 'de'],
            ['nombre' => 'ITALIANO', 'codigo' => 'it'],
            ['nombre' => 'QUECHUA', 'codigo' => 'qu'],
            ['nombre' => 'AYMARA', 'codigo' => 'ay'],
            ['nombre' => 'GUARANÍ', 'codigo' => 'gn'],
        ];

        foreach ($idiomas as $idioma) {
            DB::table('idiomas')->updateOrInsert(['nombre' => $idioma['nombre']], $idioma);
        }
    }
}
