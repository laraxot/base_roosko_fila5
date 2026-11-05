<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Database\Seeders\DemoContentSeeder;
use Modules\Fixcity\Database\Seeders\FixcityDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Popola i database Laraxot (User + Fixcity demo FO).
     *
     * Idempotente — rieseguibile senza duplicati (updateOrCreate / insertOrIgnore).
     */
    public function run(): void
    {
        $this->call([
            FixcityDatabaseSeeder::class,
            DemoContentSeeder::class,
        ]);
    }
}
