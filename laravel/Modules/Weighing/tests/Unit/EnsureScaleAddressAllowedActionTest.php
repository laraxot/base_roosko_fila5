<?php

declare(strict_types=1);

use Modules\Weighing\Actions\EnsureScaleAddressAllowedAction;
use Modules\Weighing\Exceptions\ScaleAddressNotAllowedException;
use Modules\Weighing\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

beforeEach(function (): void {
    config([
        'weighing.allowed_scale_hosts' => [],
        'weighing.scale_ports' => ['min' => 1024, 'max' => 65535],
    ]);
});

describe('EnsureScaleAddressAllowedAction', function (): void {
    test('accepts private LAN addresses when no allowlist is configured', function (): void {
        $check = function (string $host, string $expected): void {
            Assert::assertSame($expected, app(EnsureScaleAddressAllowedAction::class)->execute($host, 4001));
        };

        foreach ([
            ['192.168.1.50', '192.168.1.50'],
            ['10.0.0.5', '10.0.0.5'],
            ['172.16.3.4', '172.16.3.4'],
            ['[fd12::1]', 'fd12::1'],
        ] as $case) {
            $check(...$case);
        }
    });

    test('rejects loopback, link-local, metadata, public and short-form addresses', function (): void {
        $check = function (string $host): void {
            app(EnsureScaleAddressAllowedAction::class)->execute($host, 4001);
        };

        foreach ([
            '127.0.0.1', '::1', '::ffff:127.0.0.1', '169.254.169.254', 'fd00:ec2::254', '0.0.0.0',
            'localhost', 'scale.localhost', '2130706433', '0x7f000001', '127.1', '8.8.8.8', '', '   ',
        ] as $case) {
            TestCase::assertCallbackThrows(ScaleAddressNotAllowedException::class, static function () use ($check, $case): void {
                $check($case);
            });
        }
    });

    test('rejects ports outside the configured range', function (): void {
        $check = function (int $port): void {
            app(EnsureScaleAddressAllowedAction::class)->execute('192.168.1.50', $port);
        };

        foreach ([0, 22, 80, 70000] as $case) {
            TestCase::assertCallbackThrows(ScaleAddressNotAllowedException::class, static function () use ($check, $case): void {
                $check($case);
            });
        }
    });

    test('with an allowlist only the listed hosts and networks pass, metadata never', function (): void {
        config(['weighing.allowed_scale_hosts' => ['127.0.0.1', '10.1.0.0/16']]);
        $action = app(EnsureScaleAddressAllowedAction::class);

        Assert::assertSame('127.0.0.1', $action->execute('127.0.0.1', 4001));
        Assert::assertSame('10.1.2.3', $action->execute('10.1.2.3', 4001));

        foreach (['10.2.0.1', '192.168.1.50', '169.254.169.254'] as $host) {
            try {
                $action->execute($host, 4001);
                Assert::fail($host.' should be refused');
            } catch (ScaleAddressNotAllowedException) {
            }
        }
    });
});
