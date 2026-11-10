<?php

declare(strict_types=1);

namespace Modules\Weighing\Exceptions;

/**
 * Riga dell'indicatore non interpretabile. Il testo ricevuto non entra nel messaggio mostrato
 * all'operatore (banner di servizi qualunque, audit M2): resta in `$raw` per il log.
 */
class ScaleReadingParseException extends WeighingException
{
    public string $raw = '';

    public static function for(string $raw): self
    {
        $e = new self((string) __('weighing::exceptions.reading_unparsable'));
        $e->raw = mb_substr(trim($raw), 0, 80);

        return $e;
    }

    public static function invalidPattern(string $pattern): self
    {
        return new self((string) __('weighing::exceptions.invalid_pattern', ['pattern' => $pattern]));
    }
}
