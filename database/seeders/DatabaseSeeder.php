<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PaisesSeeder::class,
            GenerosSeeder::class,
            EstadosCivilesSeeder::class,
            TiposSeguroSeeder::class,
            TiposPlanillaSeeder::class,
            TiposContratoSeeder::class,
            PosicionesInstitucionalesSeeder::class,
            ParentescosSeeder::class,
            TiposColegioSeeder::class,
            NivelesAcademicosSeeder::class,
            TiposPosgradoSeeder::class,
            TiposProduccionIntelectualSeeder::class,
            TiposReconocimientoSeeder::class,
            TiposParticipacionEventoSeeder::class,
            TiposEventoSeeder::class,
            TiposMembresiaSeeder::class,
            IdiomasSeeder::class,
            NivelesIdiomaSeeder::class,
            SedeSeeder::class,
            RrhhCatalogosSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
