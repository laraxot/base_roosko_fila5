<?php

declare(strict_types=1);

namespace Modules\Weighing\Exceptions;

use Modules\Weighing\Models\Scale;

class ScaleConnectionException extends WeighingException
{
    public static function unreachable(Scale $scale, string $host, int $port, string $reason, int $code = 0): self
    {
        return new self((string) __('weighing::exceptions.scale_unreachable', [
            'scale' => $scale->code,
            'host' => $host,
            'port' => $port,
            'reason' => $reason,
        ]), $code);
    }

    public static function missingHost(Scale $scale): self
    {
        return new self((string) __('weighing::exceptions.scale_missing_host', ['scale' => $scale->code]));
    }

    public static function closed(Scale $scale): self
    {
        return new self((string) __('weighing::exceptions.scale_connection_closed', ['scale' => $scale->code]));
    }
}
