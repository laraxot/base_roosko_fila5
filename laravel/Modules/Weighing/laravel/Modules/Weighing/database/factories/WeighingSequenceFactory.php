<?php

declare(strict_types=1);

namespace Modules\Weighing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Weighing\Models\WeighingSequence;

/**
 * @extends Factory<WeighingSequence>
 */
class WeighingSequenceFactory extends Factory
{
    /** @var class-string<WeighingSequence> */
    protected $model = WeighingSequence::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'year' => (int) now()->format('Y'),
            'last_number' => 0,
        ];
    }
}
