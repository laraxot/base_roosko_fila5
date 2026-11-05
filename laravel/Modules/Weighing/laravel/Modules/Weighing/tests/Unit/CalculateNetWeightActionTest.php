<?php

declare(strict_types=1);

use Modules\Weighing\Actions\CalculateNetWeightAction;
use Modules\Weighing\Actions\NormalizeVehiclePlateAction;
use Modules\Weighing\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('CalculateNetWeightAction', function (): void {
    test('subtracts tare from gross rounding to the gram', function (float $gross, float $tare, float $net): void {
        Assert::assertSame($net, app(CalculateNetWeightAction::class)->execute($gross, $tare));
    })->with([
        [32_480.0, 12_260.0, 20_220.0],
        [1_000.1234, 0.1, 1_000.023],
        [0.3, 0.1, 0.2],
        [500.0, 500.0, 0.0],
    ]);

    test('rejects a negative net', function (): void {
        app(CalculateNetWeightAction::class)->execute(10_000.0, 12_000.0);
    })->throws(InvalidArgumentException::class);

    test('rejects negative weights', function (): void {
        app(CalculateNetWeightAction::class)->execute(-1.0, 0.0);
    })->throws(InvalidArgumentException::class);
});

describe('NormalizeVehiclePlateAction', function (): void {
    test('canonicalizes plates', function (?string $plate, ?string $expected): void {
        Assert::assertSame($expected, app(NormalizeVehiclePlateAction::class)->execute($plate));
    })->with([
        ['ab 123 cd', 'AB123CD'],
        ['AB-123.CD', 'AB123CD'],
        ['   ', null],
        [null, null],
    ]);
});
