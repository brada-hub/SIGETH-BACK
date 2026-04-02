<?php

namespace Database\Seeders;

use App\Models\TipoSeguro;
use Illuminate\Database\Seeder;

class TiposSeguroSeeder extends Seeder
{
    public function run(): void
    {
        $data = ['CNS', 'Caja Bancaria', 'Caja Ferroviaria', 'Caja Petrolera', 'Caja de Salud de Caminos', 'Ninguno'];

        foreach ($data as $nombre) {
            TipoSeguro::updateOrCreate(['nombre' => $nombre]);
        }
    }
}
