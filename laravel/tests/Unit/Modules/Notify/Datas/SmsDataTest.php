<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Notify\Datas\SmsData;
use Modules\Notify\Datas\SmsMessageData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('SmsData DTO', function () {
    it('creates with all fields', function () {
        $dto = new SmsData(
            to: '+393001234567',
            body: 'Test SMS message',
            from: 'MyApp',
            scheduledAt: null,
            priority: 'high',
        );

        expect($dto->to)->toBe('+393001234567');
        expect($dto->body)->toBe('Test SMS message');
        expect($dto->from)->toBe('MyApp');
        expect($dto->priority)->toBe('high');
    });

    it('creates with minimal fields', function () {
        $dto = new SmsData(
            to: '+393001234567',
            body: 'Test',
        );

        expect($dto->from)->toBeNull();
        expect($dto->priority)->toBe('normal');
    });

    it('validates phone number format', function () {
        expect(fn () => new SmsData(to: 'invalid-phone', body: 'Test'))
            ->toThrow(InvalidArgumentException::class);
    });

    it('converts to array', function () {
        $dto = new SmsData(
            to: '+393001234567',
            body: 'Test',
        );
        $array = $dto->toArray();

        expect($array)->toHaveKeys(['to', 'body', 'from', 'priority']);
    });
});

describe('SmsMessageData DTO', function () {
    it('creates message data for provider', function () {
        $dto = new SmsMessageData(
            to: '+393001234567',
            text: 'Message text',
            from: 'MyApp',
            flash: false,
            unicode: true,
        );

        expect($dto->to)->toBe('+393001234567');
        expect($dto->text)->toBe('Message text');
        expect($dto->flash)->toBeFalse();
        expect($dto->unicode)->toBeTrue();
    });

    it('handles flash SMS', function () {
        $dto = new SmsMessageData(
            to: '+393001234567',
            text: 'Flash message',
            flash: true,
        );

        expect($dto->flash)->toBeTrue();
    });

    it('handles unicode detection', function () {
        $asciiDto = new SmsMessageData(to: '+393001234567', text: 'ASCII only');
        $unicodeDto = new SmsMessageData(to: '+393001234567', text: 'Messaggio con accenti: àèé');

        expect($asciiDto->unicode)->toBeFalse();
        expect($unicodeDto->unicode)->toBeTrue();
    });

    it('calculates segment count', function () {
        $shortDto = new SmsMessageData(to: '+39', text: 'Short');
        $longDto = new SmsMessageData(to: '+39', text: str_repeat('A', 200));

        expect($shortDto->getSegmentCount())->toBe(1);
        expect($longDto->getSegmentCount())->toBeGreaterThan(1);
    });

    it('converts to array', function () {
        $dto = new SmsMessageData(to: '+393001234567', text: 'Test');
        $array = $dto->toArray();

        expect($array)->toHaveKeys(['to', 'text', 'flash', 'unicode']);
    });
});
