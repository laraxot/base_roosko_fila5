<?php

declare(strict_types=1);

namespace Modules\Weighing\Exceptions;

class ScaleReadingParseException extends WeighingException
{
    public static function for(string $raw): self
    {
        return new self((string) __('weighing::exceptions.reading_unparsable', [
            'raw' => mb_substr(trim($raw), 0, 80),
        ]));
    }

    public static function invalidPattern(string $pattern): self
    {
        return new self((string) __('weighing::exceptions.invalid_pattern', ['pattern' => $pattern]));
    }
}
