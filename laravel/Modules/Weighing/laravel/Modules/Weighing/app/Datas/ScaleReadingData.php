<?php

declare(strict_types=1);

namespace Modules\Weighing\Datas;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

/**
 * Una lettura della bilancia: peso in kg, stabilita' e riga grezza ricevuta (per l'audit in `raw_readings`).
 */
class ScaleReadingData extends Data
{
    public function __construct(
        public float $weightKg,
        public bool $stable,
        public string $raw,
        public CarbonImmutable $readAt,
    ) {}
}
