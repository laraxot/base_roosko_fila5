<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Notify\Actions\Push\SendPushNotificationAction;
use Modules\Notify\Datas\PushNotificationData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(SendPushNotificationAction::class);
    Http::fake();
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('SendPushNotificationAction', function () {
    it('sends push notification to single device', function () {
        Http::fake([
            'fcm.googleapis.com/fcm/send*' => Http::response([
                'multicast_id' => 123456789,
                'success' => 1,
                'failure' => 0,
                'canonical_ids' => 0,
                'results' => [['message_id' => 'msg-123']],
            ], 200),
        ]);

        $pushData = new PushNotificationData(
            title: 'Test Title',
            body: 'Test body',
            token: 'device-token-123',
            data: ['key' => 'value'],
        );

        $result = $this->action->execute($pushData);

        expect($result)->toBeArray()
            ->toHaveKeys(['success', 'message_id']);
        expect($result['success'])->toBeTrue();
    });

    it('sends push notification to multiple devices', function () {
        Http::fake([
            'fcm.googleapis.com/fcm/send*' => Http::response([
                'multicast_id' => 123456789,
                'success' => 2,
                'failure' => 0,
                'results' => [
                    ['message_id' => 'msg-1'],
                    ['message_id' => 'msg-2'],
                ],
            ], 200),
        ]);

        $pushData = new PushNotificationData(
            title: 'Broadcast',
            body: 'To multiple devices',
            tokens: ['token-1', 'token-2'],
        );

        $result = $this->action->execute($pushData);

        expect($result['success'])->toBe(2);
    });

    it('sends push notification to topic', function () {
        Http::fake([
            'fcm.googleapis.com/fcm/send*' => Http::response([
                'message_id' => 'topic-msg-123',
            ], 200),
        ]);

        $pushData = new PushNotificationData(
            title: 'Topic Message',
            body: 'For all subscribers',
            topic: 'news',
        );

        $result = $this->action->execute($pushData);

        expect($result)->toHaveKey('message_id');
    });

    it('handles FCM error responses', function () {
        Http::fake([
            'fcm.googleapis.com/fcm/send*' => Http::response([
                'multicast_id' => 123,
                'success' => 0,
                'failure' => 1,
                'results' => [['error' => 'NotRegistered']],
            ], 200),
        ]);

        $pushData = new PushNotificationData(
            title: 'Test',
            body: 'Test',
            token: 'invalid-token',
        );

        $result = $this->action->execute($pushData);

        expect($result['success'])->toBe(0);
        expect($result['failure'])->toBe(1);
    });

    it('includes custom data payload', function () {
        Http::fake([
            'fcm.googleapis.com/fcm/send*' => Http::response([
                'success' => 1,
                'results' => [['message_id' => 'msg-1']],
            ], 200),
        ]);

        $pushData = new PushNotificationData(
            title: 'Data Test',
            body: 'With data',
            token: 'token-123',
            data: ['action' => 'open_order', 'order_id' => 123],
        );

        $result = $this->action->execute($pushData);

        expect($result['success'])->toBeTrue();
    });

    it('sets priority and TTL', function () {
        Http::fake([
            'fcm.googleapis.com/fcm/send*' => Http::response(['success' => 1], 200),
        ]);

        $pushData = new PushNotificationData(
            title: 'High Priority',
            body: 'Urgent',
            token: 'token-123',
            priority: 'high',
            ttl: 3600,
        );

        $result = $this->action->execute($pushData);

        expect($result['success'])->toBeTrue();
    });
});
