<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    public function run(): void
    {
        $sedes = [
            [
                'nombre' => 'COBIJA',
                'sigla' => 'CBJ',
                'departamento_id' => 'PD',
                'departamento' => 'PANDO',
                'ciudad' => 'COBIJA',
                'activo' => 1,
            ],
            [
                'nombre' => 'COCHABAMBA',
                'sigla' => 'COC',
                'departamento_id' => 'CB',
                'departamento' => 'COCHABAMBA',
                'ciudad' => 'COCHABAMBA',
                'activo' => 1,
            ],
            [
                'nombre' => 'EL ALTO',
                'sigla' => 'EAL',
                'departamento_id' => 'LP',
                'departamento' => 'LA PAZ',
                'ciudad' => 'EL ALTO',
                'activo' => 1,
            ],
            [
                'nombre' => 'GUAYARAMERIN',
                'sigla' => 'GYA',
                'departamento_id' => 'BN',
                'departamento' => 'BENI',
                'ciudad' => 'GUAYARAMERIN',
                'activo' => 1,
            ],
            [
                'nombre' => 'IVIRGARZAMA',
                'sigla' => 'IVI',
                'departamento_id' => 'CB',
                'departamento' => 'COCHABAMBA',
                'ciudad' => 'IVIRGARZAMA',
                'activo' => 1,
            ],
            [
                'nombre' => 'LA PAZ',
                'sigla' => 'LPZ',
                'departamento_id' => 'LP',
                'departamento' => 'LA PAZ',
                'ciudad' => 'LA PAZ',
                'activo' => 1,
            ],
            [
                'nombre' => 'NACIONAL',
                'sigla' => 'NAC',
                'departamento_id' => null,
                'departamento' => 'NACIONAL',
                'ciudad' => 'NACIONAL',
                'activo' => 1,
            ],
            [
                'nombre' => 'PUERTO QUIJARRO',
                'sigla' => 'PQJ',
                'departamento_id' => 'SC',
                'departamento' => 'SANTA CRUZ',
                'ciudad' => 'PUERTO QUIJARRO',
                'activo' => 1,
            ],
            [
                'nombre' => 'SANTA CRUZ',
                'sigla' => 'SCZ',
                'departamento_id' => 'SC',
                'departamento' => 'SANTA CRUZ',
                'ciudad' => 'SANTA CRUZ',
                'activo' => 1,
            ],
        ];

        foreach ($sedes as $sede) {
            Sede::updateOrCreate(['sigla' => $sede['sigla']], $sede);
        }
    }
}
