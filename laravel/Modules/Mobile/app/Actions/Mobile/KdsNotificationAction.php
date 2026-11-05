<?php

declare(strict_types=1);

namespace Modules\Mobile\Actions\Mobile;

use Illuminate\Support\Facades\Notification;
use Modules\Mobile\Models\OrderQueue;
use Spatie\QueueableAction\QueueableAction;

/**
 * Action for sending order to Kitchen Display System (KDS).
 * Pushes notification to kitchen screens and mobile devices.
 */
class KdsNotificationAction
{
    use QueueableAction;

    public function execute(int $orderId, bool $isNewOrder = true): void
    {
        $order = OrderQueue::findOrFail($orderId);

        $items = $order->order_data['items'] ?? [];
        $items = is_array($items) ? $items : [];

        $payload = [
            'order_id' => $order->id,
            'table_number' => '1',  // Simplified for demo
            'table_name' => 'Tavolo 1',
            'waiter_name' => 'Operatore Demo',
            'items' => array_map(static function (mixed $item): array {
                $item = is_array($item) ? $item : [];
                return [
                    'id' => $item['id'] ?? uniqid(),
                    'product_name' => $item['name'] ?? 'Prodotto Demo',
                    'quantity' => $item['quantity'] ?? 1,
                    'notes' => $item['notes'] ?? '',
                    'modifiers' => $item['modifiers'] ?? [],
                    'status' => $item['status'] ?? 'pending',
                    'course' => $item['course'] ?? 'main',
                ];
            }, $items),
            'notes' => is_string($order->order_data['notes'] ?? null) ? $order->order_data['notes'] : '',
            'priority' => $this->calculatePriority($order),
            'timestamp' => now()->toISOString(),
            'type' => $isNewOrder ? 'new_order' : 'update',
        ];

        // Broadcast to kitchen channel
        broadcast(new \Modules\Mobile\Notifications\KdsOrderNotification($payload))
            ->toOthers();

        // Also send push notification to kitchen staff
        Notification::send(
            $this->getKitchenStaff(),
            new \Modules\Mobile\Notifications\KdsPushNotification($payload)
        );
    }

    private function calculatePriority(OrderQueue $order): int
    {
        $priority = 0;
        $items = $order->order_data['items'] ?? [];
        $items = is_array($items) ? $items : [];

        // Rush orders get higher priority
        $notes = is_string($order->order_data['notes'] ?? null) ? $order->order_data['notes'] : '';
        if (stripos($notes, 'rush') !== false) {
            $priority += 100;
        }

        // Simple priority based on item count
        if (count($items) > 3) {
            $priority += 30;
        } elseif (count($items) > 1) {
            $priority += 15;
        }

        return $priority;
    }

    /** @return \Illuminate\Support\Collection<int, object> */
    private function getKitchenStaff(): \Illuminate\Support\Collection
    {
        /** @var \Illuminate\Support\Collection<int, object> $staff */
        $staff = collect();

        return $staff;
    }
}
