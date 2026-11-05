<?php

declare(strict_types=1);

namespace Modules\Mobile\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Mobile\Models\OrderQueue;
use Modules\Mobile\Models\WaiterSession;
use function Safe\json_encode;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create waiter sessions
        $waiter1 = WaiterSession::create([
            'device_name' => 'Marco Rossi', 'is_active' => true,
            'platform' => 'android', 'token' => 'demo-marco',
        ]);

        $waiter2 = WaiterSession::create([
            'device_name' => 'Anna Verdi', 'is_active' => true,
            'platform' => 'android', 'token' => 'demo-anna',
        ]);

        // Create orders
        OrderQueue::create([
            'table_id' => 1,
            'waiter_session_id' => $waiter1->id, 'status' => OrderQueue::STATUS_PENDING,
            'order_data' => ['items' => [['name' => 'Caffè', 'quantity' => 1, 'price' => 1.50]], 'total' => 1.50],
            'sync_attempts' => 0,
        ]);

        OrderQueue::create([
            'table_id' => 2,
            'waiter_session_id' => $waiter2->id, 'status' => OrderQueue::STATUS_PENDING,
            'order_data' => ['items' => [['name' => 'Pasta al Pomodoro', 'quantity' => 1, 'price' => 8.00]], 'total' => 8.00],
            'sync_attempts' => 0,
        ]);
    }
}
