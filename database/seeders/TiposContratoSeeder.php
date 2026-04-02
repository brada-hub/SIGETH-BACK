<?php

namespace Database\Seeders;

use App\Models\TipoContrato;
use Illuminate\Database\Seeder;

class TiposContratoSeeder extends Seeder
{
    public function run(): void
    {
        $data = ['Plazo Fijo', 'Indefinido', 'Prestación de Servicios', 'Consultoría', 'Periodo Académico'];

        foreach ($data as $nombre) {
            TipoContrato::updateOrCreate(['nombre' => $nombre], ['activo' => 1]);
        }
    }
}
