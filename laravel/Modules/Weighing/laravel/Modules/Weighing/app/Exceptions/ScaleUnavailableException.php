<?php

declare(strict_types=1);

namespace Modules\Weighing\Exceptions;

use Modules\Weighing\Models\Scale;

/**
 * La bilancia non si puo' usare: inattiva, manuale (peso da digitare), verificazione scaduta, portata superata.
 */
class ScaleUnavailableException extends WeighingException
{
    public static function manual(Scale $scale): self
    {
        return new self((string) __('weighing::exceptions.scale_manual', ['scale' => $scale->code]));
    }

    public static function inactive(Scale $scale): self
    {
        return new self((string) __('weighing::exceptions.scale_inactive', ['scale' => $scale->code]));
    }

    public static function verificationExpired(Scale $scale): self
    {
        return new self((string) __('weighing::exceptions.scale_verification_expired', [
            'scale' => $scale->code,
            'date' => $scale->verified_until?->toDateString() ?? '—',
        ]));
    }

    public static function overload(Scale $scale, float $kg): self
    {
        return new self((string) __('weighing::exceptions.scale_overload', [
            'scale' => $scale->code,
            'kg' => number_format($kg, 3, ',', '.'),
            'max' => number_format($scale->maxCapacityKg(), 3, ',', '.'),
        ]));
    }
}
