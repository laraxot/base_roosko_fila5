<?php

declare(strict_types=1);

namespace Modules\Mobile\Actions\Mobile;

use InvalidArgumentException;
use Modules\Mobile\Models\OrderQueue;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\QueueableAction\QueueableAction;

/**
 * Presa ordine del cameriere, offline-first.
 *
 * Accoda l'ordine (tavolo, righe, QR) in `mobile_order_queue` con stato pending:
 * SyncOfflineOrdersAction lo invia al server quando torna la connessione.
 * Ritorna l'id della riga in coda.
 */
class TakeOrderAction
{
    use QueueableAction;

    /** @param array<string, mixed> $payload */
    public function execute(array $payload): string
    {
        $waiterSessionId = SafeStringCastAction::cast($payload['waiter_session_id'] ?? '');
        $waiterId = SafeStringCastAction::cast($payload['waiter_id'] ?? '');
        $tableId = SafeStringCastAction::cast($payload['table_id'] ?? '');
        $items = $payload['items'] ?? [];
        $qrCode = SafeStringCastAction::cast($payload['qr_code'] ?? '');

        if ($waiterSessionId === '' || $tableId === '') {
            throw new InvalidArgumentException('waiter_session_id e table_id sono obbligatori');
        }

        $order = OrderQueue::query()->create([
            'waiter_session_id' => $waiterSessionId,
            'table_id' => (int) $tableId,
            'order_data' => [
                'waiter_id' => $waiterId,
                'table_id' => $tableId,
                'items' => is_array($items) ? $items : [],
                'qr_code' => $qrCode,
                'status' => 'open',
                'source' => 'mobile',
                'created_at_utc' => date('c'),
            ],
            'status' => OrderQueue::STATUS_PENDING,
        ]);

        return $order->id;
    }
}
