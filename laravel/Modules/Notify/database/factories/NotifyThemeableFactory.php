<?php

declare(strict_types=1);

namespace Modules\Notify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Notify\Models\NotifyTheme;
use Modules\Notify\Models\NotifyThemeable;

/**
 * NotifyThemeable Factory
 *
 * @extends Factory<NotifyThemeable>
 */
class NotifyThemeableFactory extends Factory
{
    protected $model = NotifyThemeable::class;
    /**
     * @return array<string, mixed>
     */

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'notify_theme_id' => NotifyTheme::factory(),
<<<<<<< HEAD
            'themeable_type' => $this->faker->randomElement([
=======
            'model_type' => $this->faker->randomElement([
>>>>>>> laraxot/dev
                'Modules\\User\\Models\\User',
                'Modules\\User\\Models\\User', // Generic fallback instead of project-specific
                'Modules\\User\\Models\\User', // Generic fallback instead of project-specific
            ]),
<<<<<<< HEAD
            'themeable_id' => $this->faker->randomNumber()];
=======
            'model_id' => $this->faker->randomNumber()];
>>>>>>> laraxot/dev
    }

    public function forUser(): static
    {
        return $this->state(fn (array $_attributes): array => [
<<<<<<< HEAD
            'themeable_type' => 'Modules\\User\\Models\\User']);
=======
            'model_type' => 'Modules\\User\\Models\\User']);
>>>>>>> laraxot/dev
    }

    public function forPatient(): static
    {
        return $this->state(fn (array $_attributes): array => [
<<<<<<< HEAD
            'themeable_type' => 'Modules\\User\\Models\\User', // Generic fallback
=======
            'model_type' => 'Modules\\User\\Models\\User', // Generic fallback
>>>>>>> laraxot/dev
        ]);
    }
}
