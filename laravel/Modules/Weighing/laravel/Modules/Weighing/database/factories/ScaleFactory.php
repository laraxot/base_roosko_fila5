<?php

declare(strict_types=1);

namespace Modules\Weighing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Weighing\Enums\ScaleDriverEnum;
use Modules\Weighing\Enums\ScaleTypeEnum;
use Modules\Weighing\Models\Scale;

/**
 * Di default: pesa a ponte 60 t, divisione 20 kg, driver fake, verificata per un anno.
 *
 * @extends Factory<Scale>
 */
class ScaleFactory extends Factory
{
    /** @var class-string<Scale> */
    protected $model = Scale::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'SC-'.$this->faker->unique()->numerify('####'),
            'name' => 'Pesa '.$this->faker->word(),
            'type' => ScaleTypeEnum::WEIGHBRIDGE,
            'driver' => ScaleDriverEnum::FAKE,
            'host' => null,
            'port' => null,
            'max_capacity_kg' => '60000.000',
            'resolution_kg' => '20.000',
            'verified_until' => now()->addYear()->toDateString(),
            'is_active' => true,
        ];
    }

    public function platform(): static
    {
        return $this->state(fn (array $_attributes): array => [
            'type' => ScaleTypeEnum::PLATFORM,
            'max_capacity_kg' => '3000.000',
            'resolution_kg' => '1.000',
        ]);
    }

    public function manual(): static
    {
        return $this->state(fn (array $_attributes): array => ['driver' => ScaleDriverEnum::MANUAL]);
    }

    public function tcp(string $host, int $port): static
    {
        return $this->state(fn (array $_attributes): array => [
            'driver' => ScaleDriverEnum::TCP_ASCII,
            'host' => $host,
            'port' => $port,
        ]);
    }

    public function verificationExpired(): static
    {
        return $this->state(fn (array $_attributes): array => ['verified_until' => now()->subDay()->toDateString()]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $_attributes): array => ['is_active' => false]);
    }
}
