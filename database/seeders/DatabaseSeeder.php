<?php

namespace Database\Seeders;

use Database\Seeders\projects\AdrienGarnierSeeder;
use Database\Seeders\projects\AudreyKabreSeeder;
use Database\Seeders\projects\BettinaElmalkiSeeder;
use Database\Seeders\projects\CaroleGrandinSeeder;
use Database\Seeders\projects\EleonoreRokiaSeeder;
use Database\Seeders\projects\EmmaReynosoAvalosSeeder;
use Database\Seeders\projects\FatehHaddadouSeeder;
use Database\Seeders\projects\FlorentOdetSeeder;
use Database\Seeders\projects\HoaTranSeeder;
use Database\Seeders\projects\JeanPhilippeCunySeeder;
use Database\Seeders\projects\JenniferRafinonSeeder;
use Database\Seeders\projects\JosaphatKodessaSeeder;
use Database\Seeders\projects\LydiaSoudaySeeder;
use Database\Seeders\projects\VanessaCostaSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class, // Ajout du seeder pour le professeur
            JeanPhilippeCunySeeder::class,
            EleonoreRokiaSeeder::class,
            VanessaCostaSeeder::class,
            AdrienGarnierSeeder::class,
            AudreyKabreSeeder::class,
            BettinaElmalkiSeeder::class,
            CaroleGrandinSeeder::class,
            EmmaReynosoAvalosSeeder::class,
            FatehHaddadouSeeder::class,
            FlorentOdetSeeder::class,
            HoaTranSeeder::class,
            JenniferRafinonSeeder::class,
            JosaphatKodessaSeeder::class,
            LydiaSoudaySeeder::class,
        ]);
    }
}
