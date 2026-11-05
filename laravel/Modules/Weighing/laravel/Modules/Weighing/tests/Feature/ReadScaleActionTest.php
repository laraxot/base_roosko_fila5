<?php

declare(strict_types=1);

use Modules\Weighing\Actions\ReadScaleAction;
use Modules\Weighing\Exceptions\ScaleUnavailableException;
use Modules\Weighing\Models\Scale;
use Modules\Weighing\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

beforeEach(function (): void {
    config(['weighing.fake' => ['weight_kg' => 18_420.0, 'stable' => true]]);
});

describe('ReadScaleAction', function (): void {
    test('reads the fake driver value', function (): void {
        $reading = app(ReadScaleAction::class)->execute(Scale::factory()->create());

        Assert::assertSame(18_420.0, $reading->weightKg);
        Assert::assertTrue($reading->stable);
        Assert::assertStringStartsWith('ST,GS,', $reading->raw);
    });

    test('refuses to read a manual scale', function (): void {
        app(ReadScaleAction::class)->execute(Scale::factory()->manual()->create());
    })->throws(ScaleUnavailableException::class);

    test('refuses an inactive scale', function (): void {
        app(ReadScaleAction::class)->execute(Scale::factory()->inactive()->create());
    })->throws(ScaleUnavailableException::class);

    test('refuses a reading above the capacity', function (): void {
        app(ReadScaleAction::class)->execute(Scale::factory()->platform()->create());
    })->throws(ScaleUnavailableException::class);
});
