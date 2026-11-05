<?php

declare(strict_types=1);

namespace Modules\Mobile\Actions\Mobile;

use Illuminate\Support\Facades\Http;
use Modules\Mobile\Models\OrderQueue;
use Spatie\QueueableAction\QueueableAction;

/**
 * Sync offline order queue with the server.
 * Runs via scheduled task or when connection restored.
 */
class SyncOfflineOrdersAction
{
    use QueueableAction;

    /** @return array{synced: list<int|string>, failed: list<int|string>, total: int, timestamp: string} */
    public function execute(string $waiterSessionId): array
    {
        $pending = \Modules\Mobile\Models\OrderQueue::where('waiter_session_id', $waiterSessionId)
            ->where('status', OrderQueue::STATUS_PENDING)
            ->get();

        $synced = [];
        $failed = [];

        foreach ($pending as $queueItem) {
            try {
                $response = Http::timeout(15)
                    ->post((is_string(config('mobile.api_url')) ? config('mobile.api_url') : 'https://api.sottana.com') . '/api/mobile/orders', [
                        'order_data' => $queueItem->order_data,
                        'waiter_session_id' => $waiterSessionId,
                        'table_id' => $queueItem->table_id,
                        'queue_id' => $queueItem->id,
                    ]);

                if ($response->successful()) {
                    $queueItem->update([
                        'status' => OrderQueue::STATUS_SYNCED,
                        'sync_attempts' => $queueItem->sync_attempts + 1,
                        'last_sync_at' => now(),
                    ]);
                    $synced[] = $queueItem->id;
                } else {
                    $this->markFailed($queueItem, $response->body());
                    $failed[] = $queueItem->id;
                }
            } catch (\Throwable $e) {
                $this->markFailed($queueItem, $e->getMessage());
                $failed[] = $queueItem->id;
            }
        }

        return [
            'synced' => $synced,
            'failed' => $failed,
            'total' => $pending->count(),
            'timestamp' => now()->toISOString() ?? now()->toAtomString(),
        ];
    }

    private function markFailed(\Modules\Mobile\Models\OrderQueue $queueItem, string $error): void
    {
        $attempts = $queueItem->sync_attempts + 1;
        $queueItem->update([
            'status' => OrderQueue::STATUS_FAILED,
            'sync_attempts' => $attempts,
            'error_message' => $error,
            'last_sync_at' => now(),
        ]);
    }
}
