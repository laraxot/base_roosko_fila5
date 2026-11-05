<?php

declare(strict_types=1);

namespace Modules\Mobile\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KdsPushNotification extends Notification
{
    use Queueable;

    /** @param array<string, mixed> $payload */
    public function __construct(private readonly array $payload) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['onesignal'];
    }

    /** @return array<string, mixed> */
    public function toOneSignal(object $notifiable): array
    {
        return [
            'app_id' => config('mobile.onesignal_app_id'),
            'include_external_user_ids' => [$this->payload['waiter_id'] ?? ''],
            'headings' => ['en' => 'New Order - Table '.$this->scalar($this->payload['table_number'] ?? null)],
            'contents' => ['en' => 'New order from Table '.$this->scalar($this->payload['table_number'] ?? null)],
            'data' => $this->payload,
        ];
    }

    private function scalar(mixed $value): string
    {
        return is_scalar($value) ? (string) $value : '';
    }
}
