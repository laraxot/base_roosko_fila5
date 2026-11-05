<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Modules\Weighing\Exceptions\ScaleUnavailableException;
use Modules\Weighing\Models\Scale;
use Spatie\QueueableAction\QueueableAction;

/**
 * Una pesata si registra solo su una bilancia attiva, con verificazione periodica valida
 * (se `config('weighing.require_valid_verification')`) e entro la portata massima.
 *
 * @throws ScaleUnavailableException
 */
class EnsureScaleUsableAction
{
    use QueueableAction;

    public function execute(Scale $scale, ?float $kg = null): void
    {
        if (! $scale->is_active) {
            throw ScaleUnavailableException::inactive($scale);
        }

        if (config()->boolean('weighing.require_valid_verification', true) && ! $scale->isVerifiedAt(now()->toImmutable())) {
            throw ScaleUnavailableException::verificationExpired($scale);
        }

        if ($kg !== null && $scale->maxCapacityKg() > 0 && $kg > $scale->maxCapacityKg()) {
            throw ScaleUnavailableException::overload($scale, $kg);
        }
    }
}
