<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Illuminate\Database\Eloquent\Model;
use Modules\Weighing\Enums\WeighingDirectionEnum;
use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Weighing\Exceptions\ScaleUnavailableException;
use Modules\Weighing\Models\Scale;
use Modules\Weighing\Models\Weighing;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Pesata a passaggio singolo (bilancia a pianale, gru, carrello): il peso letto e' gia' netto.
 * Si registra direttamente chiusa, direzione `internal`, lordo = netto e tara = 0.
 *
 * @throws ScaleUnavailableException
 */
class RecordSingleWeighingAction
{
    use QueueableAction;

    public function execute(float $netKg, ?Scale $scale = null, ?Model $weighable = null, ?string $rawReading = null): Weighing
    {
        Assert::greaterThan($netKg, 0.0, (string) __('weighing::exceptions.weight_not_positive'));
        if ($scale !== null) {
            app(EnsureScaleUsableAction::class)->execute($scale, $netKg);
        }
        if ($weighable !== null) {
            Assert::true($weighable->exists, (string) __('weighing::exceptions.weighable_not_saved'));
        }

        return (new Weighing)->getConnection()->transaction(function () use ($netKg, $scale, $weighable, $rawReading): Weighing {
            $now = now()->toImmutable();
            $kg = Weighing::kgToStorage($netKg);

            $weighing = new Weighing([
                'ticket_number' => app(GenerateWeighingTicketNumberAction::class)->execute($now),
                'direction' => WeighingDirectionEnum::INTERNAL,
                'status' => WeighingStatusEnum::CLOSED,
                'scale_id' => $scale?->id,
                'gross_kg' => $kg,
                'tare_kg' => Weighing::kgToStorage(0.0),
                'net_kg' => $kg,
                'gross_weighed_at' => $now,
                'closed_at' => $now,
                'raw_readings' => [[
                    'phase' => 'net',
                    'kg' => round($netKg, 3),
                    'raw' => $rawReading,
                    'scale_id' => $scale?->id,
                    'at' => $now->toIso8601String(),
                ]],
            ]);

            if ($weighable !== null) {
                $weighing->weighable()->associate($weighable);
            }

            $weighing->save();

            return $weighing;
        }, GenerateWeighingTicketNumberAction::ATTEMPTS);
    }
}
