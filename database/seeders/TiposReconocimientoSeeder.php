<?php

namespace Database\Seeders;

use App\Models\TipoReconocimiento;
use Illuminate\Database\Seeder;

class TiposReconocimientoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Premio Nacional', 'Premio Internacional', 'Mención de Honor', 
            'Reconocimiento Institucional', 'Distinción Académica'
        ];

        foreach ($data as $nombre) {
            TipoReconocimiento::updateOrCreate(['nombre' => $nombre]);
        }
    }
}
