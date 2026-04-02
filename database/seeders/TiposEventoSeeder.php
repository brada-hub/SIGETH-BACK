<?php

namespace Database\Seeders;

use App\Models\TipoEvento;
use Illuminate\Database\Seeder;

class TiposEventoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Congreso', 'Seminario', 'Taller', 'Simposio', 
            'Charla', 'Jornada', 'Conferencia', 'Foro'
        ];

        foreach ($data as $nombre) {
            TipoEvento::updateOrCreate(['nombre' => $nombre]);
        }
    }
}
