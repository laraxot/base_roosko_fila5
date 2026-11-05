<?php

declare(strict_types=1);

namespace Modules\Mobile\Filament\Widgets\Mobile;

use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Illuminate\Support\Facades\Cache;
use Modules\Mobile\Models\WaiterSession;
use Modules\Mobile\Models\OrderQueue;

class OrderWidget extends XotBaseWidget
{
    protected static ?string $pollingInterval = '10s';

    public static function getColumns(): int
    {
        return 3;
    }

    protected function getViewData(): array
    {
        $waiterSessionId = session('waiter_session_id');
        $pendingOrders = 0;
        $failedOrders = 0;
        $activeWaiters = 0;

        if ($waiterSessionId) {
            $activeWaiters = WaiterSession::where('id', $waiterSessionId)
                ->where('is_active', true)
                ->count();

            $pendingOrders = OrderQueue::where('status', OrderQueue::STATUS_PENDING)
                ->where('waiter_session_id', $waiterSessionId)
                ->count();

            $failedOrders = OrderQueue::where('status', OrderQueue::STATUS_FAILED)
                ->where('waiter_session_id', $waiterSessionId)
                ->count();
        }

        return [
            'pending_orders' => $pendingOrders,
            'failed_orders' => $failedOrders,
            'active_waiters' => $activeWaiters,
            'has_connection' => $this->checkConnection(),
        ];
    }

    private function checkConnection(): bool
    {
        return (bool) Cache::get('mobile_queue_sync_connection', false);
    }
}
