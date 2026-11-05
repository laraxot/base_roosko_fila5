<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Notify\Actions\SendRecordNotificationAction;
use Modules\User\Models\User;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(SendRecordNotificationAction::class);
    $this->user = User::factory()->create();
});

describe('SendRecordNotificationAction', function () {
    it('creates database notification record', function () {
        $notification = $this->action->handle(
            $this->user,
            'Record notification message',
            'info',
            ['key' => 'value']
        );

        expect($notification)->not->toBeNull();
        expect($notification->type)->toBe('info');
        expect($notification->message)->toBe('Record notification message');
        expect($notification->data['key'])->toBe('value');
        expect($notification->status)->toBe('sent');
        expect($notification->sent_at)->not->toBeNull();
    });

    it('uses default type if not specified', function () {
        $notification = $this->action->handle($this->user, 'Test message');

        expect($notification->type)->toBe('generic');
    });

    it('associates with correct notifiable', function () {
        $notification = $this->action->handle($this->user, 'Test');

        expect($notification->notifiable_type)->toBe(get_class($this->user));
        expect($notification->notifiable_id)->toBe($this->user->getKey());
    });

    it('stores user_id if available', function () {
        $notification = $this->action->handle($this->user, 'Test');

        expect($notification->user_id)->toBe($this->user->id);
    });

    it('handles different notification types', function () {
        $types = ['info', 'warning', 'error', 'success', 'custom_type'];

        foreach ($types as $type) {
            $notification = $this->action->handle($this->user, "Message for $type", $type);
            expect($notification->type)->toBe($type);
        }
    });

    it('stores additional data', function () {
        $data = [
            'action_url' => '/admin/orders/123',
            'priority' => 'high',
            'metadata' => ['source' => 'api'],
        ];

        $notification = $this->action->handle($this->user, 'Test', 'info', $data);

        expect($notification->data['action_url'])->toBe('/admin/orders/123');
        expect($notification->data['priority'])->toBe('high');
        expect($notification->data['metadata']['source'])->toBe('api');
    });
});
