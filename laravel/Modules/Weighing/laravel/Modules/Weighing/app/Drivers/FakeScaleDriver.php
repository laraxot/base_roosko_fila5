<?php

declare(strict_types=1);

namespace Modules\Weighing\Drivers;

use Modules\Weighing\Contracts\ScaleDriverContract;
use Modules\Weighing\Datas\ScaleReadingData;
use Modules\Weighing\Models\Scale;

/**
 * Lettura fissa da `config('weighing.fake')`, per test e demo senza hardware.
 */
class FakeScaleDriver implements ScaleDriverContract
{
    public function read(Scale $scale): ScaleReadingData
    {
        $kg = round(config()->float('weighing.fake.weight_kg', 12500.0), 3);
        $stable = config()->boolean('weighing.fake.stable', true);

        return new ScaleReadingData(
            weightKg: $kg,
            stable: $stable,
            raw: sprintf('%s,GS,%+.3fkg', $stable ? 'ST' : 'US', $kg),
            readAt: now()->toImmutable(),
        );
    }
}
