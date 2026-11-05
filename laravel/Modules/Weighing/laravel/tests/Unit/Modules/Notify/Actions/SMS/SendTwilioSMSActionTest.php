<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Notify\Actions\SMS\SendTwilioSMSAction;
use Modules\Notify\Datas\SmsData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(SendTwilioSMSAction::class);
    Http::fake();
    config(['notify.sms.twilio.account_sid' => 'test-sid', 'notify.sms.twilio.auth_token' => 'test-token', 'notify.sms.twilio.from' => '+15551234567']);
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('SendTwilioSMSAction', function () {
    it('sends SMS via Twilio API', function () {
        Http::fake([
            'api.twilio.com/2010-04-01/Accounts/test-sid/Messages.json*' => Http::response([
                'sid' => 'SM123456789',
                'status' => 'queued',
                'to' => '+393001234567',
                'from' => '+15551234567',
                'body' => 'Test message',
            ], 201),
        ]);

        $smsData = new SmsData(
            to: '+393001234567',
            body: 'Test message',
        );

        $result = $this->action->execute($smsData);

        expect($result)->toBeArray()
            ->toHaveKeys(['sid', 'status', 'to', 'from']);
        expect($result['sid'])->toBe('SM123456789');
        expect($result['status'])->toBe('queued');
    });

    it('handles Twilio API error', function () {
        Http::fake([
            'api.twilio.com/2010-04-01/Accounts/test-sid/Messages.json*' => Http::response([
                'code' => 21211,
                'message' => 'The \'To\' number +393001234567 is not a valid phone number.',
                'more_info' => 'https://www.twilio.com/docs/errors/21211',
                'status' => 400,
            ], 400),
        ]);

        $smsData = new SmsData(to: 'invalid', body: 'Test');

        expect(fn () => $this->action->execute($smsData))
            ->toThrow(Exception::class);
    });

    it('handles rate limiting', function () {
        Http::fake([
            'api.twilio.com/2010-04-01/Accounts/test-sid/Messages.json*' => Http::response([
                'code' => 20429,
                'message' => 'Too Many Requests',
                'status' => 429,
            ], 429),
        ]);

        $smsData = new SmsData(to: '+393001234567', body: 'Test');

        expect(fn () => $this->action->execute($smsData))
            ->toThrow(Exception::class);
    });

    it('includes media URLs when provided', function () {
        Http::fake([
            'api.twilio.com/2010-04-01/Accounts/test-sid/Messages.json*' => Http::response([
                'sid' => 'SM123',
                'status' => 'queued',
                'num_media' => '1',
            ], 201),
        ]);

        $smsData = new SmsData(
            to: '+393001234567',
            body: 'Test with media',
            mediaUrls: ['https://example.com/image.jpg'],
        );

        $result = $this->action->execute($smsData);

        expect($result['num_media'])->toBe('1');
    });

    it('validates required config', function () {
        config(['notify.sms.twilio.account_sid' => null]);

        $smsData = new SmsData(to: '+393001234567', body: 'Test');

        expect(fn () => $this->action->execute($smsData))
            ->toThrow(Exception::class, 'Twilio configuration missing');
    });
});
