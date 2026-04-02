<?php

namespace Database\Seeders;

use App\Models\EstadoCivil;
use Illuminate\Database\Seeder;

class EstadosCivilesSeeder extends Seeder
{
    public function run(): void
    {
        $data = ['Soltero', 'Casado', 'Divorciado', 'Viudo', 'Unión libre'];

        foreach ($data as $nombre) {
            EstadoCivil::updateOrCreate(['nombre' => $nombre]);
        }
    }
}
