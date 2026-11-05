<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Notify\Datas\PushNotificationData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('PushNotificationData DTO', function () {
    it('creates with all fields for single device', function () {
        $dto = new PushNotificationData(
            title: 'Test Title',
            body: 'Test body',
            token: 'device-token-123',
            data: ['action' => 'open', 'id' => 123],
            image: 'https://example.com/image.png',
            icon: 'ic_notification',
            color: '#FF0000',
            sound: 'default',
            priority: 'high',
            ttl: 3600,
            collapseKey: 'updates',
        );

        expect($dto->title)->toBe('Test Title');
        expect($dto->body)->toBe('Test body');
        expect($dto->token)->toBe('device-token-123');
        expect($dto->data['action'])->toBe('open');
        expect($dto->image)->toBe('https://example.com/image.png');
        expect($dto->priority)->toBe('high');
        expect($dto->ttl)->toBe(3600);
    });

    it('creates for multiple devices', function () {
        $dto = new PushNotificationData(
            title: 'Broadcast',
            body: 'To multiple',
            tokens: ['token-1', 'token-2', 'token-3'],
        );

        expect($dto->tokens)->toHaveCount(3);
        expect($dto->token)->toBeNull();
    });

    it('creates for topic', function () {
        $dto = new PushNotificationData(
            title: 'Topic Message',
            body: 'For subscribers',
            topic: 'news',
        );

        expect($dto->topic)->toBe('news');
        expect($dto->token)->toBeNull();
        expect($dto->tokens)->toBeEmpty();
    });

    it('creates with minimal fields', function () {
        $dto = new PushNotificationData(
            title: 'Minimal',
            body: 'Test',
        );

        expect($dto->data)->toBeEmpty();
        expect($dto->priority)->toBe('normal');
    });

    it('validates required fields', function () {
        // Must have at least one target: token, tokens, or topic
        expect(fn () => new PushNotificationData(title: 'Test', body: 'Test'))
            ->toThrow(InvalidArgumentException::class, 'At least one target required');
    });

    it('converts to array for FCM', function () {
        $dto = new PushNotificationData(
            title: 'FCM Test',
            body: 'FCM Body',
            token: 'token-123',
            data: ['key' => 'value'],
        );
        $array = $dto->toFcmArray();

        expect($array)->toHaveKeys(['to', 'notification', 'data', 'priority']);
        expect($array['notification']['title'])->toBe('FCM Test');
        expect($array['notification']['body'])->toBe('FCM Body');
    });

    it('converts to array for multiple tokens', function () {
        $dto = new PushNotificationData(
            title: 'Multi',
            body: 'Multi',
            tokens: ['t1', 't2'],
        );
        $array = $dto->toFcmArray();

        expect($array)->toHaveKey('registration_ids');
        expect($array['registration_ids'])->toBe(['t1', 't2']);
    });
});
