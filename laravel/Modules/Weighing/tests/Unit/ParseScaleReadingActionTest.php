<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Modules\Weighing\Actions\ParseScaleReadingAction;
use Modules\Weighing\Exceptions\ScaleReadingParseException;
use Modules\Weighing\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

beforeEach(function (): void {
    config([
        'weighing.tcp_ascii.weight_pattern' => ParseScaleReadingAction::DEFAULT_PATTERN,
        'weighing.tcp_ascii.stable_marker' => 'ST',
        'weighing.tcp_ascii.unit_factor' => 1.0,
    ]);
});

describe('ParseScaleReadingAction', function (): void {
    test('reads weight and stability from common indicator formats', function (): void {
        $check = function (string $raw, float $kg, bool $stable): void {
            $reading = app(ParseScaleReadingAction::class)->execute($raw);

            Assert::assertSame($kg, $reading->weightKg);
            Assert::assertSame($stable, $reading->stable);
            Assert::assertSame($raw, $reading->raw);
        };

        foreach ([
            'stable gross with spaces after sign' => ["ST,GS,+  12340 kg\r\n", 12340.0, true],
            'unstable' => ["US,GS,+  12360 kg\r\n", 12360.0, false],
            'decimal point' => ['ST,NT,+0001234.5kg', 1234.5, true],
            'decimal comma' => ['ST,GS,  987,25 kg', 987.25, true],
            'negative' => ['ST,NT,-   20 kg', -20.0, true],
            'device address prefix uses the last number' => ['01ST,GS,+001234kg', 1234.0, true],
            'bare number' => ['  4520  ', 4520.0, false],
        ] as $case) {
            $check(...$case);
        }
    });

    test('applies the configured unit factor', function (): void {
        config(['weighing.tcp_ascii.unit_factor' => 1000.0]);

        Assert::assertSame(12.34 * 1000, app(ParseScaleReadingAction::class)->execute('ST,GS,+12.340 t')->weightKg);
    });

    test('keeps the given reading time', function (): void {
        $at = CarbonImmutable::parse('2026-11-04 10:00:00');

        Assert::assertTrue($at->equalTo(app(ParseScaleReadingAction::class)->execute('ST 100', $at)->readAt));
    });

    test('rejects lines without a weight', function (): void {
        $check = function (string $raw): void {
            app(ParseScaleReadingAction::class)->execute($raw);
        };

        foreach (['OL', 'ST,GS,--------', ''] as $case) {
            TestCase::assertCallbackThrows(ScaleReadingParseException::class, static function () use ($check, $case): void {
                $check($case);
            });
        }
    });

    test('rejects an invalid configured pattern', function (): void {
        TestCase::assertCallbackThrows(ScaleReadingParseException::class, function (): void {
            config(['weighing.tcp_ascii.weight_pattern' => '/(unclosed']);

            app(ParseScaleReadingAction::class)->execute('ST 100');
        });
    });
});
