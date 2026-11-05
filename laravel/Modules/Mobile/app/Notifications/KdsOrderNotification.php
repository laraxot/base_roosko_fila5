<?php

declare(strict_types=1);

namespace Modules\Mobile\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class KdsOrderNotification extends Notification
{
    use Queueable;

    /** @param array<string, mixed> $payload */
    public function __construct(private readonly array $payload) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'order_id' => $this->payload['order_id'],
            'table_number' => $this->payload['table_number'],
            'items' => $this->payload['items'],
            'type' => 'kds_order',
            'timestamp' => now()->toISOString(),
        ]);
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New Order',
            'body' => sprintf('Table %s - %d items', $this->scalar($this->payload['table_number'] ?? null), count(is_array($this->payload['items'] ?? null) ? $this->payload['items'] : [])),
            'data' => $this->payload,
        ];
    }

    private function scalar(mixed $value): string
    {
        return is_scalar($value) ? (string) $value : '';
    }
}
