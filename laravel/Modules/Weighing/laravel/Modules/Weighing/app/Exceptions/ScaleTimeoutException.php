<?php

declare(strict_types=1);

namespace Modules\Weighing\Exceptions;

use Modules\Weighing\Models\Scale;

class ScaleTimeoutException extends WeighingException
{
    public static function noData(Scale $scale, float $seconds): self
    {
        return new self((string) __('weighing::exceptions.scale_timeout', [
            'scale' => $scale->code,
            'seconds' => $seconds,
        ]));
    }
}
