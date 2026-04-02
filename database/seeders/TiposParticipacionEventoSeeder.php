<?php

namespace Database\Seeders;

use App\Models\TipoParticipacionEvento;
use Illuminate\Database\Seeder;

class TiposParticipacionEventoSeeder extends Seeder
{
    public function run(): void
    {
        $data = ['Ponente', 'Asistente', 'Organizador', 'Moderador', 'Conferencista invitado'];

        foreach ($data as $nombre) {
            TipoParticipacionEvento::updateOrCreate(['nombre' => $nombre]);
        }
    }
}
