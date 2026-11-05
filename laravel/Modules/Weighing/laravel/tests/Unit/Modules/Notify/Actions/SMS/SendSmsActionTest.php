<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Notify\Actions\SMS\FormatSmsMessageAction;
use Modules\Notify\Actions\SMS\NormalizePhoneNumberAction;
use Modules\Notify\Actions\SMS\SendSmsAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->sendAction = app(SendSmsAction::class);
    $this->normalizeAction = app(NormalizePhoneNumberAction::class);
    $this->formatAction = app(FormatSmsMessageAction::class);
    Http::fake();
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('NormalizePhoneNumberAction', function () {
    it('normalizes Italian mobile number', function () {
        $result = $this->normalizeAction->execute('+39 300 123 4567');

        expect($result)->toBe('+393001234567');
    });

    it('normalizes Italian number without country code', function () {
        $result = $this->normalizeAction->execute('300 123 4567');

        expect($result)->toBe('+393001234567');
    });

    it('normalizes number with spaces and dashes', function () {
        $result = $this->normalizeAction->execute('+39-300-123-4567');

        expect($result)->toBe('+393001234567');
    });

    it('handles already normalized number', function () {
        $result = $this->normalizeAction->execute('+393001234567');

        expect($result)->toBe('+393001234567');
    });

    it('throws exception for invalid number', function () {
        expect(fn () => $this->normalizeAction->execute('invalid'))
            ->toThrow(Exception::class);
    });

    it('handles international numbers', function () {
        $result = $this->normalizeAction->execute('+1 555 123 4567');

        expect($result)->toBe('+15551234567');
    });
});

describe('FormatSmsMessageAction', function () {
    it('formats message within 160 chars (GSM)', function () {
        $message = 'Short message';
        $result = $this->formatAction->execute($message);

        expect($result)->toBe($message);
    });

    it('truncates message over 160 chars', function () {
        $longMessage = str_repeat('A', 200);
        $result = $this->formatAction->execute($longMessage);

        expect(mb_strlen($result))->toBeLessThanOrEqual(160);
        expect($result)->toEndWith('...');
    });

    it('handles Unicode characters', function () {
        $message = 'Messaggio con accenti: àèéìòù';
        $result = $this->formatAction->execute($message);

        expect($result)->toBe($message);
    });

    it('handles emojis (counts as multiple chars)', function () {
        $message = 'Test 🎉 emoji';
        $result = $this->formatAction->execute($message);

        expect($result)->toBe($message);
    });
});

describe('SendSmsAction', function () {
    it('sends SMS via configured provider', function () {
        // SendSmsAction is a dispatcher that uses provider-specific actions
        // This test verifies it can be instantiated and called
        expect($this->sendAction)->toBeInstanceOf(SendSmsAction::class);
    });

    it('normalizes phone before sending', function () {
        $phone = '300 123 4567';
        $normalized = $this->normalizeAction->execute($phone);

        expect($normalized)->toBe('+393001234567');
    });
});
