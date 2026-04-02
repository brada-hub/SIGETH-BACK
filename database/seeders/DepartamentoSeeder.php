<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $deps = [
            ['id' => 'LP', 'nombre' => 'LA PAZ'],
            ['id' => 'OR', 'nombre' => 'ORURO'],
            ['id' => 'PT', 'nombre' => 'POTOSI'],
            ['id' => 'CB', 'nombre' => 'COCHABAMBA'],
            ['id' => 'CH', 'nombre' => 'CHUQUISACA'],
            ['id' => 'TJ', 'nombre' => 'TARIJA'],
            ['id' => 'PD', 'nombre' => 'PANDO'],
            ['id' => 'BN', 'nombre' => 'BENI'],
            ['id' => 'SC', 'nombre' => 'SANTA CRUZ'],
        ];

        foreach ($deps as $dep) {
            DB::table('departamentos')->updateOrInsert(['id' => $dep['id']], $dep);
        }
    }
}
