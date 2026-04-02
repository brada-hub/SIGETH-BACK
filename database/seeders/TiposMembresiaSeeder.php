<?php

namespace Database\Seeders;

use App\Models\TipoMembresia;
use Illuminate\Database\Seeder;

class TiposMembresiaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Colegio Profesional', 'Sociedad Científica', 'Red Académica', 
            'Asociación Gremial', 'Organismo Internacional'
        ];

        foreach ($data as $nombre) {
            TipoMembresia::updateOrCreate(['nombre' => $nombre]);
        }
    }
}
