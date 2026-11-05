<?php

declare(strict_types=1);

namespace Modules\Weighing\Exceptions;

use Modules\Weighing\Models\Weighing;

/**
 * Tentativo di modificare pesi o ticket di una pesata chiusa/annullata, o di cancellarla.
 */
class WeighingLockedException extends WeighingException
{
    /**
     * @param  list<string>  $attributes
     */
    public static function for(Weighing $weighing, array $attributes = []): self
    {
        return new self((string) __('weighing::exceptions.weighing_locked', [
            'ticket' => $weighing->ticket_number,
            'attributes' => implode(', ', $attributes),
        ]));
    }
}
