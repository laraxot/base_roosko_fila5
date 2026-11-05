<?php
namespace Modules\Mobile\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Mobile\Models\WaiterSession;

/** @extends Factory<WaiterSession> */
class WaiterSessionFactory extends Factory
{
    protected $model = WaiterSession::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}