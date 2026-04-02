<?php

namespace Database\Seeders;

use App\Models\Parentesco;
use Illuminate\Database\Seeder;

class ParentescosSeeder extends Seeder
{
    public function run(): void
    {
        $data = ['Cónyuge', 'Hijo/a', 'Padre', 'Madre', 'Hermano/a', 'Abuelo/a', 'Nieto/a'];

        foreach ($data as $nombre) {
            Parentesco::updateOrCreate(['nombre' => $nombre]);
        }
    }
}
