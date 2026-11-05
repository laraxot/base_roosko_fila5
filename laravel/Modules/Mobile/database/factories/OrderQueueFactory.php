<?php
declare(strict_types=1);

namespace Modules\Mobile\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Mobile\Models\OrderQueue;

/**
 * @extends Factory<OrderQueue>
 */
class OrderQueueFactory extends Factory
{
    /** @var class-string<OrderQueue> */
    protected $model = OrderQueue::class;

    public function pending(): OrderQueueFactory
    {
        return $this->state(['status' => 'pending']);
    }

    public function synced(): OrderQueueFactory
    {
        return $this->state(['status' => 'synced']);
    }

    public function failed(): OrderQueueFactory
    {
        return $this->state(['status' => 'failed']);
    }

    public function definition(): array
    {
        return [
            'status' => 'pending',
        ];
    }
}
