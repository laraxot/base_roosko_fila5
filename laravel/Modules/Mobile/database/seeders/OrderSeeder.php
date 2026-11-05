<?php

declare(strict_types=1);

namespace Modules\Mobile\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Mobile\Models\OrderQueue;
use Modules\Mobile\Models\WaiterSession;
use function Safe\json_encode;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $waiter = WaiterSession::firstOrCreate(
            ['device_name' => 'Marco'],
            ['is_active' => true, 'platform' => 'android', 'token' => 'demo-marco']
        );

        OrderQueue::query()->create([
            'waiter_session_id' => $waiter->id,
            'status' => OrderQueue::STATUS_PENDING,
            'order_data' => json_encode(['items' => ['caffè', 'cornetto'], 'total' => 4.50]),
            'sync_attempts' => 0,
        ]);

        OrderQueue::query()->create([
            'waiter_session_id' => $waiter->id,
            'status' => OrderQueue::STATUS_PENDING,
            'order_data' => json_encode(['items' => ['pasta al pomodoro'], 'total' => 12.00]),
            'sync_attempts' => 0,
        ]);
    }
}
