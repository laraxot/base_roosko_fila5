<?php

declare(strict_types=1);

namespace Modules\Weighing\Exceptions;

use Modules\Weighing\Models\Scale;

class ScaleConnectionException extends WeighingException
{
    /**
     * Il motivo tecnico (errore del socket) non va all'operatore: distinguerebbe porte chiuse, filtrate
     * o host assenti. Il driver lo scrive nel log.
     */
    public static function unreachable(Scale $scale, string $host, int $port, int $code = 0): self
    {
        return new self((string) __('weighing::exceptions.scale_unreachable', [
            'scale' => $scale->code,
            'host' => $host,
            'port' => (string) $port,
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
