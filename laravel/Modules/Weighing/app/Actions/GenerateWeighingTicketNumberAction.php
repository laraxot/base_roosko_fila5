<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Carbon\CarbonImmutable;
use Modules\Weighing\Models\WeighingSequence;
use Spatie\QueueableAction\QueueableAction;

/**
 * Prossimo numero ticket `W-{anno}-{progressivo a 6 cifre}`.
 *
 * Il contatore dell'anno (`weighing_sequences`) si crea se manca (`insertOrIgnore`, sicuro in
 * concorrenza grazie all'unique su `year`) e si incrementa sotto `lockForUpdate`. Va chiamata dentro
 * la transazione che salva la pesata: il lock resta fino al commit, quindi due pesate concorrenti
 * non possono avere lo stesso numero e un rollback non lascia buchi.
 */
class GenerateWeighingTicketNumberAction
{
    use QueueableAction;

    public function execute(?CarbonImmutable $at = null): string
    {
        $year = ($at ?? now()->toImmutable())->year;

        return (new WeighingSequence)->getConnection()->transaction(static function () use ($year): string {
            $now = now();
            WeighingSequence::query()->insertOrIgnore([
                ['year' => $year, 'last_number' => 0, 'created_at' => $now, 'updated_at' => $now],
            ]);

            $sequence = WeighingSequence::query()
                ->where('year', $year)
                ->lockForUpdate()
                ->firstOrFail();

            $sequence->last_number = $sequence->last_number + 1;
            $sequence->save();

            return sprintf('W-%d-%06d', $year, $sequence->last_number);
        });
    }
}
