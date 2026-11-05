<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Weighing\Exceptions\ScaleUnavailableException;
use Modules\Weighing\Models\Scale;
use Modules\Weighing\Models\Weighing;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Chiude la pesata con il secondo peso (tara per inbound/internal, lordo per outbound) e calcola
 * `net_kg = gross_kg - tare_kg` ({@see CalculateNetWeightAction}, Assert >= 0).
 *
 * La riga si rilegge sotto `lockForUpdate`: due chiusure concorrenti dello stesso ticket non passano.
 * Aggiorna anche l'istanza ricevuta, che quindi riflette lo stato salvato.
 *
 * @throws ScaleUnavailableException
 */
class CloseWeighingAction
{
    use QueueableAction;

    public function execute(Weighing $weighing, float $secondKg, ?string $rawReading = null): Weighing
    {
        Assert::greaterThan($secondKg, 0.0, (string) __('weighing::exceptions.weight_not_positive'));

        $closed = (new Weighing)->getConnection()->transaction(function () use ($weighing, $secondKg, $rawReading): Weighing {
            $locked = Weighing::query()->lockForUpdate()->findOrFail($weighing->id);

            Assert::true(
                $locked->status->canTransitionTo(WeighingStatusEnum::CLOSED),
                (string) __('weighing::exceptions.invalid_transition', [
                    'ticket' => $locked->ticket_number,
                    'from' => $locked->status->getLabel(),
                    'to' => WeighingStatusEnum::CLOSED->getLabel(),
                ]),
            );

            $scale = $locked->scale;
            if ($scale instanceof Scale) {
                app(EnsureScaleUsableAction::class)->execute($scale, $secondKg);
            }

            $now = now()->toImmutable();
            $firstIsGross = $locked->direction->firstWeightIsGross();
            $grossKg = $firstIsGross ? ($locked->grossKg() ?? 0.0) : $secondKg;
            $tareKg = $firstIsGross ? $secondKg : ($locked->tareKg() ?? 0.0);

            $netKg = app(CalculateNetWeightAction::class)->execute($grossKg, $tareKg);

            if ($firstIsGross) {
                $locked->tare_kg = Weighing::kgToStorage($tareKg);
                $locked->tare_weighed_at = $now;
            } else {
                $locked->gross_kg = Weighing::kgToStorage($grossKg);
                $locked->gross_weighed_at = $now;
            }

            $locked->net_kg = Weighing::kgToStorage($netKg);
            $locked->status = WeighingStatusEnum::CLOSED;
            $locked->closed_at = $now;
            $locked->raw_readings = [
                ...($locked->raw_readings ?? []),
                [
                    'phase' => $firstIsGross ? 'tare' : 'gross',
                    'kg' => round($secondKg, 3),
                    'raw' => $rawReading,
                    'scale_id' => $locked->scale_id,
                    'at' => $now->toIso8601String(),
                ],
            ];
            $locked->save();

            return $locked;
        });

        $weighing->setRawAttributes($closed->getAttributes(), true);

        return $weighing;
    }
}
