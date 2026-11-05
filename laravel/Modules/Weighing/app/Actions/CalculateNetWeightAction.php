<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Netto = lordo - tara, arrotondato al grammo. Funzione pura (testata in unit).
 * Un netto negativo significa pesi invertiti o tara sbagliata: si rifiuta.
 */
class CalculateNetWeightAction
{
    use QueueableAction;

    public function execute(float $grossKg, float $tareKg): float
    {
        Assert::greaterThanEq($grossKg, 0.0);
        Assert::greaterThanEq($tareKg, 0.0);

        $net = round($grossKg - $tareKg, 3);

        Assert::greaterThanEq($net, 0.0, (string) __('weighing::exceptions.negative_net', [
            'gross' => number_format($grossKg, 3, ',', '.'),
            'tare' => number_format($tareKg, 3, ',', '.'),
        ]));

        return $net + 0.0;
    }
}
