<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Modules\Weighing\Actions\AttachWeighingAction;
use Modules\Weighing\Actions\CloseWeighingAction;
use Modules\Weighing\Actions\GenerateWeighingTicketNumberAction;
use Modules\Weighing\Actions\OpenWeighingAction;
use Modules\Weighing\Actions\RecordSingleWeighingAction;
use Modules\Weighing\Actions\VoidWeighingAction;
use Modules\Weighing\Enums\WeighingDirectionEnum;
use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Weighing\Exceptions\ScaleUnavailableException;
use Modules\Weighing\Exceptions\WeighingLockedException;
use Modules\Weighing\Models\Scale;
use Modules\Weighing\Models\Weighing;
use Modules\Weighing\Models\WeighingSequence;
use Modules\Weighing\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

beforeEach(function (): void {
    config(['weighing.require_valid_verification' => true]);
});

describe('OpenWeighingAction / CloseWeighingAction', function (): void {
    test('inbound: first weight is gross, second is tare, net is computed', function (): void {
        $scale = Scale::factory()->create();

        $weighing = app(OpenWeighingAction::class)->execute(
            WeighingDirectionEnum::INBOUND, 32_480.0, $scale, 'ab 123 cd', null, 'ST,GS,+  32480 kg',
        );

        Assert::assertMatchesRegularExpression('/^W-\d{4}-\d{6}$/', $weighing->ticket_number);
        Assert::assertSame(WeighingStatusEnum::OPEN, $weighing->status);
        Assert::assertSame(32_480.0, $weighing->grossKg());
        Assert::assertNull($weighing->tareKg());
        Assert::assertSame('AB123CD', $weighing->vehicle_plate);
        Assert::assertSame('ST,GS,+  32480 kg', $weighing->raw_readings[0]['raw'] ?? null);

        app(CloseWeighingAction::class)->execute($weighing, 12_260.0, 'ST,GS,+  12260 kg');

        Assert::assertSame(WeighingStatusEnum::CLOSED, $weighing->status);
        Assert::assertSame(12_260.0, $weighing->tareKg());
        Assert::assertSame(20_220.0, $weighing->netKg());
        Assert::assertNotNull($weighing->closed_at);
        Assert::assertCount(2, $weighing->raw_readings ?? []);
        Assert::assertSame(20_220.0, Weighing::query()->findOrFail($weighing->id)->netKg());
    });

    test('outbound: first weight is tare, second is gross', function (): void {
        $weighing = app(OpenWeighingAction::class)->execute(WeighingDirectionEnum::OUTBOUND, 11_000.0);
        app(CloseWeighingAction::class)->execute($weighing, 29_500.0);

        Assert::assertSame(29_500.0, $weighing->grossKg());
        Assert::assertSame(11_000.0, $weighing->tareKg());
        Assert::assertSame(18_500.0, $weighing->netKg());
    });

    test('internal behaves like inbound', function (): void {
        $weighing = app(OpenWeighingAction::class)->execute(WeighingDirectionEnum::INTERNAL, 2_000.0);

        Assert::assertSame(2_000.0, $weighing->grossKg());
    });

    test('rejects a second weight that makes the net negative', function (): void {
        TestCase::assertCallbackThrows(InvalidArgumentException::class, function (): void {
            $weighing = app(OpenWeighingAction::class)->execute(WeighingDirectionEnum::INBOUND, 10_000.0);

            app(CloseWeighingAction::class)->execute($weighing, 12_000.0);
        });
    });

    test('cannot close a ticket twice', function (): void {
        TestCase::assertCallbackThrows(InvalidArgumentException::class, function (): void {
            $weighing = app(OpenWeighingAction::class)->execute(WeighingDirectionEnum::INBOUND, 20_000.0);
            app(CloseWeighingAction::class)->execute($weighing, 8_000.0);

            app(CloseWeighingAction::class)->execute($weighing, 7_000.0);
        });
    });

    test('refuses a scale with expired verification', function (): void {
        TestCase::assertCallbackThrows(ScaleUnavailableException::class, function (): void {
            app(OpenWeighingAction::class)->execute(WeighingDirectionEnum::INBOUND, 20_000.0, Scale::factory()->verificationExpired()->create());
        });
    });

    test('refuses a weight above the scale capacity', function (): void {
        TestCase::assertCallbackThrows(ScaleUnavailableException::class, function (): void {
            app(OpenWeighingAction::class)->execute(WeighingDirectionEnum::INBOUND, 70_000.0, Scale::factory()->create());
        });
    });

    test('refuses a non positive weight', function (): void {
        TestCase::assertCallbackThrows(InvalidArgumentException::class, function (): void {
            app(OpenWeighingAction::class)->execute(WeighingDirectionEnum::INBOUND, 0.0);
        });
    });
});

describe('GenerateWeighingTicketNumberAction', function (): void {
    test('numbers tickets progressively within the year', function (): void {
        $first = app(GenerateWeighingTicketNumberAction::class)->execute();
        $second = app(GenerateWeighingTicketNumberAction::class)->execute();

        Assert::assertSame((int) substr($first, -6) + 1, (int) substr($second, -6));
        Assert::assertSame(substr($first, 0, 7), substr($second, 0, 7));
    });

    test('creates the counter of a new year with a single statement and starts from 1', function (): void {
        $year = 2091;
        WeighingSequence::query()->where('year', $year)->delete();
        $at = CarbonImmutable::create($year, 3, 1);
        Assert::assertInstanceOf(CarbonImmutable::class, $at);

        Assert::assertSame('W-2091-000001', app(GenerateWeighingTicketNumberAction::class)->execute($at));
        Assert::assertSame('W-2091-000002', app(GenerateWeighingTicketNumberAction::class)->execute($at));
        Assert::assertSame(1, WeighingSequence::query()->where('year', $year)->count());
        Assert::assertSame(2, WeighingSequence::query()->where('year', $year)->value('last_number'));
    });

    test('rolls the number back with the caller transaction, leaving no gap', function (): void {
        $at = CarbonImmutable::create(2092, 1, 1);
        Assert::assertInstanceOf(CarbonImmutable::class, $at);
        $connection = (new WeighingSequence)->getConnection();
        $first = app(GenerateWeighingTicketNumberAction::class)->execute($at);

        try {
            $connection->transaction(static function () use ($at): void {
                app(GenerateWeighingTicketNumberAction::class)->execute($at);

                throw new RuntimeException('rollback');
            });
        } catch (RuntimeException) {
        }

        Assert::assertSame((int) substr($first, -6) + 1, (int) substr(app(GenerateWeighingTicketNumberAction::class)->execute($at), -6));
    });
});

describe('RecordSingleWeighingAction', function (): void {
    test('records a closed internal weighing with net = gross and zero tare', function (): void {
        $weighing = app(RecordSingleWeighingAction::class)->execute(845.5, Scale::factory()->platform()->create());

        Assert::assertSame(WeighingStatusEnum::CLOSED, $weighing->status);
        Assert::assertSame(WeighingDirectionEnum::INTERNAL, $weighing->direction);
        Assert::assertSame(845.5, $weighing->netKg());
        Assert::assertSame(845.5, $weighing->grossKg());
        Assert::assertSame(0.0, $weighing->tareKg());
    });
});

describe('VoidWeighingAction / AttachWeighingAction / immutability', function (): void {
    test('voids a closed ticket keeping its weights', function (): void {
        $weighing = app(RecordSingleWeighingAction::class)->execute(500.0);

        app(VoidWeighingAction::class)->execute($weighing, 'Bilancia scarica');

        $weighing->refresh();
        Assert::assertSame(WeighingStatusEnum::VOIDED, $weighing->status);
        Assert::assertSame('Bilancia scarica', $weighing->void_reason);
        Assert::assertSame(500.0, $weighing->netKg());
    });

    test('attaches a closed weighing to a domain model', function (): void {
        $weighing = app(RecordSingleWeighingAction::class)->execute(500.0);
        $target = Scale::factory()->create();

        app(AttachWeighingAction::class)->execute($weighing, $target);

        $weighing->refresh();
        Assert::assertSame($target->getMorphClass(), $weighing->weighable_type);
        Assert::assertSame($target->id, $weighing->weighable_id);
    });

    test('forbids changing the weights of a closed ticket', function (): void {
        TestCase::assertCallbackThrows(WeighingLockedException::class, function (): void {
            $weighing = app(RecordSingleWeighingAction::class)->execute(500.0);

            $weighing->net_kg = Weighing::kgToStorage(600.0);
            $weighing->save();
        });
    });

    test('forbids voiding twice', function (): void {
        TestCase::assertCallbackThrows(InvalidArgumentException::class, function (): void {
            $weighing = app(RecordSingleWeighingAction::class)->execute(500.0);
            app(VoidWeighingAction::class)->execute($weighing, 'Errore');

            app(VoidWeighingAction::class)->execute($weighing, 'Ancora');
        });
    });
});
