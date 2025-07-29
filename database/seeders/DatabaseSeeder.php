<?php

namespace Database\Seeders;

use Database\Seeders\Projects\EleonoreRokiaSeeder;
use Database\Seeders\Projects\EmmaReynosoSeeder;
use Database\Seeders\Projects\JeanPhilippeCunySeeder;
use Database\Seeders\Projects\LydiaSoulaySeeder;
use Database\Seeders\Projects\VanessaCostaSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmmaReynosoSeeder::class,
            JeanPhilippeCunySeeder::class,
            EleonoreRokiaSeeder::class,
            VanessaCostaSeeder::class,
            LydiaSoulaySeeder::class,
        ]);
    }
}