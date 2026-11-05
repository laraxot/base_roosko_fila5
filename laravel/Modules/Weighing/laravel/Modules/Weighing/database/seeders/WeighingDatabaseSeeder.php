<?php

declare(strict_types=1);

namespace Modules\Weighing\Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Orchestratore Weighing. I ticket non si seminano: nascono dalle Actions con numerazione sotto lock.
 */
class WeighingDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ScaleSeeder::class,
        ]);
    }
}
