<?php

declare(strict_types=1);

namespace Modules\Weighing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Weighing\Enums\ScaleDriverEnum;
use Modules\Weighing\Enums\ScaleTypeEnum;
use Modules\Weighing\Models\Scale;

/**
 * Due bilance di esempio: pesa a ponte manuale e bilancia a pianale "fake" per le demo.
 * Data di verificazione e collegamento reali si impostano dalla UI.
 */
class ScaleSeeder extends Seeder
{
    public function run(): void
    {
        Scale::query()->updateOrCreate(['code' => 'PESA-01'], [
            'name' => 'Pesa a ponte ingresso',
            'type' => ScaleTypeEnum::WEIGHBRIDGE,
            'driver' => ScaleDriverEnum::MANUAL,
            'max_capacity_kg' => '60000.000',
            'resolution_kg' => '20.000',
            'verified_until' => null,
            'is_active' => true,
        ]);

        Scale::query()->updateOrCreate(['code' => 'PIANALE-01'], [
            'name' => 'Bilancia a pianale (demo)',
            'type' => ScaleTypeEnum::PLATFORM,
            'driver' => ScaleDriverEnum::FAKE,
            'max_capacity_kg' => '3000.000',
            'resolution_kg' => '1.000',
            'verified_until' => now()->addYear()->toDateString(),
            'is_active' => true,
        ]);
    }
}
