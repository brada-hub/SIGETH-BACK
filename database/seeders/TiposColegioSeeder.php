<?php

namespace Database\Seeders;

use App\Models\TipoColegio;
use Illuminate\Database\Seeder;

class TiposColegioSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Fiscal', 'Privado', 'Urbano', 'Rural', 
            'Fiscal Urbano', 'Fiscal Rural', 
            'Privado Urbano', 'Privado Rural'
        ];

        foreach ($data as $nombre) {
            TipoColegio::updateOrCreate(['nombre' => $nombre]);
        }
    }
}
