<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Spatie\QueueableAction\QueueableAction;

/**
 * Targa in forma canonica (maiuscolo, senza spazi, trattini e punti): "ab 123 cd" → "AB123CD".
 * Null per stringa vuota.
 */
class NormalizeVehiclePlateAction
{
    use QueueableAction;

    public function execute(?string $plate): ?string
    {
        if ($plate === null) {
            return null;
        }

        $normalized = strtoupper(preg_replace('/[\s.\-]+/', '', $plate) ?? $plate);

        return $normalized === '' ? null : $normalized;
    }
}
