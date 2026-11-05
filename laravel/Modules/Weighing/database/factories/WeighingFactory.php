<?php

declare(strict_types=1);

namespace Modules\Weighing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Weighing\Enums\WeighingDirectionEnum;
use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Weighing\Models\Weighing;

/**
 * Per i dati di test. Nel codice applicativo i ticket nascono solo da OpenWeighingAction /
 * RecordSingleWeighingAction (numero progressivo sotto lock): qui il numero e' casuale con prefisso T.
 *
 * @extends Factory<Weighing>
 */
class WeighingFactory extends Factory
{
    /** @var class-string<Weighing> */
    protected $model = Weighing::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gross = $this->faker->numberBetween(15_000, 40_000);

        return [
            'ticket_number' => 'T-'.$this->faker->unique()->numerify('########'),
            'direction' => WeighingDirectionEnum::INBOUND,
            'status' => WeighingStatusEnum::OPEN,
            'scale_id' => null,
            'vehicle_plate' => strtoupper($this->faker->bothify('??###??')),
            'gross_kg' => Weighing::kgToStorage((float) $gross),
            'gross_weighed_at' => now()->toImmutable(),
            'raw_readings' => [],
        ];
    }

    public function closed(float $tareKg = 12_000.0): static
    {
        return $this->state(function (array $attributes) use ($tareKg): array {
            $gross = is_numeric($attributes['gross_kg'] ?? null) ? (float) $attributes['gross_kg'] : 30_000.0;

            return [
                'status' => WeighingStatusEnum::CLOSED,
                'tare_kg' => Weighing::kgToStorage($tareKg),
                'net_kg' => Weighing::kgToStorage(max(0.0, $gross - $tareKg)),
                'tare_weighed_at' => now()->toImmutable(),
                'closed_at' => now()->toImmutable(),
            ];
        });
    }
}
