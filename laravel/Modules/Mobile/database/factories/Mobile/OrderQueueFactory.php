<?php

namespace Modules\Mobile\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Mobile\Models\OrderQueue;
use Modules\Mobile\Models\WaiterSession;

/** @extends Factory<OrderQueue> */
class OrderQueueFactory extends Factory
{
    protected $model = OrderQueue::class;

    public function definition(): array
    {
        return [
            'waiter_session_id' => static fn (): string => WaiterSession::query()
                ->firstOrCreate(['device_id' => 'factory-device'])
                ->id,
            'table_id' => 1,
            'status' => OrderQueue::STATUS_PENDING,
            'order_data' => ['items' => ['caffè', 'cornetto'], 'total' => 4.50],
            'sync_attempts' => 0,
        ];
    }

    public function pending(): static { return $this->state(['status' => OrderQueue::STATUS_PENDING]); }
    public function synced(): static { return $this->state(['status' => OrderQueue::STATUS_SYNCED]); }
    public function failed(): static { return $this->state(['status' => OrderQueue::STATUS_FAILED]); }
}
