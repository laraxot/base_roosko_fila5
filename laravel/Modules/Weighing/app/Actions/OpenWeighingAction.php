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
 * Apre una pesata a due passaggi con la prima pesata.
 *
 * - inbound / internal: primo peso = lordo; outbound: primo peso = tara;
 * - ticket `W-{anno}-{progr}` generato nella stessa transazione ({@see GenerateWeighingTicketNumberAction}),
 *   ripetuta fino a 3 volte su deadlock quando e' la transazione esterna;
 * - targa normalizzata; bilancia (se indicata) attiva, verificata ed entro portata;
 * - `$rawReading` (riga ricevuta dall'indicatore, se letta da bilancia) finisce in `raw_readings`.
 *
 * @throws ScaleUnavailableException
 */
class OpenWeighingAction
{
    use QueueableAction;

    public function execute(
        WeighingDirectionEnum $direction,
        float $firstKg,
        ?Scale $scale = null,
        ?string $vehiclePlate = null,
        ?Model $weighable = null,
        ?string $rawReading = null,
    ): Weighing {
        Assert::greaterThan($firstKg, 0.0, (string) __('weighing::exceptions.weight_not_positive'));
        if ($scale !== null) {
            app(EnsureScaleUsableAction::class)->execute($scale, $firstKg);
        }
        if ($weighable !== null) {
            Assert::true($weighable->exists, (string) __('weighing::exceptions.weighable_not_saved'));
        }

        $plate = app(NormalizeVehiclePlateAction::class)->execute($vehiclePlate);

        return (new Weighing)->getConnection()->transaction(function () use ($direction, $firstKg, $scale, $plate, $weighable, $rawReading): Weighing {
            $now = now()->toImmutable();

            $weighing = new Weighing([
                'ticket_number' => app(GenerateWeighingTicketNumberAction::class)->execute($now),
                'direction' => $direction,
                'status' => WeighingStatusEnum::OPEN,
                'scale_id' => $scale?->id,
                'vehicle_plate' => $plate,
                'raw_readings' => [[
                    'phase' => $direction->firstWeightIsGross() ? 'gross' : 'tare',
                    'kg' => round($firstKg, 3),
                    'raw' => $rawReading,
                    'scale_id' => $scale?->id,
                    'at' => $now->toIso8601String(),
                ]],
            ]);

            if ($direction->firstWeightIsGross()) {
                $weighing->gross_kg = Weighing::kgToStorage($firstKg);
                $weighing->gross_weighed_at = $now;
            } else {
                $weighing->tare_kg = Weighing::kgToStorage($firstKg);
                $weighing->tare_weighed_at = $now;
            }

            if ($weighable !== null) {
                $weighing->weighable()->associate($weighable);
            }

            $weighing->save();

            return $weighing;
        }, GenerateWeighingTicketNumberAction::ATTEMPTS);
    }
}
