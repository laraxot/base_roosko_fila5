<?php

declare(strict_types=1);

use Modules\Weighing\Drivers\FakeScaleDriver;
use Modules\Weighing\Drivers\ManualScaleDriver;
use Modules\Weighing\Drivers\TcpAsciiScaleDriver;
use Modules\Weighing\Enums\ScaleDriverEnum;
use Modules\Weighing\Enums\WeighingDirectionEnum;
use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Weighing\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('WeighingStatusEnum', function (): void {
    test('allows only the documented transitions', function (): void {
        $check = function (WeighingStatusEnum $from, WeighingStatusEnum $to, bool $allowed): void {
            Assert::assertSame($allowed, $from->canTransitionTo($to));
        };

        foreach ([
            [WeighingStatusEnum::OPEN, WeighingStatusEnum::CLOSED, true],
            [WeighingStatusEnum::OPEN, WeighingStatusEnum::VOIDED, true],
            [WeighingStatusEnum::CLOSED, WeighingStatusEnum::VOIDED, true],
            [WeighingStatusEnum::CLOSED, WeighingStatusEnum::OPEN, false],
            [WeighingStatusEnum::VOIDED, WeighingStatusEnum::CLOSED, false],
            [WeighingStatusEnum::OPEN, WeighingStatusEnum::OPEN, false],
        ] as $case) {
            $check(...$case);
        }
    });
});

describe('WeighingDirectionEnum', function (): void {
    test('knows which weight comes first', function (): void {
        Assert::assertTrue(WeighingDirectionEnum::INBOUND->firstWeightIsGross());
        Assert::assertTrue(WeighingDirectionEnum::INTERNAL->firstWeightIsGross());
        Assert::assertFalse(WeighingDirectionEnum::OUTBOUND->firstWeightIsGross());
    });
});

describe('ScaleDriverEnum', function (): void {
    test('resolves each driver class', function (): void {
        Assert::assertSame(ManualScaleDriver::class, ScaleDriverEnum::MANUAL->driverClass());
        Assert::assertSame(TcpAsciiScaleDriver::class, ScaleDriverEnum::TCP_ASCII->driverClass());
        Assert::assertSame(FakeScaleDriver::class, ScaleDriverEnum::FAKE->driverClass());
        Assert::assertFalse(ScaleDriverEnum::MANUAL->canRead());
    });
});
