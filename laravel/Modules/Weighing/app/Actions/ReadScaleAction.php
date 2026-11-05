<?php

declare(strict_types=1);

namespace Modules\Weighing\Actions;

use Modules\Weighing\Contracts\ScaleDriverContract;
use Modules\Weighing\Datas\ScaleReadingData;
use Modules\Weighing\Exceptions\ScaleUnavailableException;
use Modules\Weighing\Exceptions\WeighingException;
use Modules\Weighing\Models\Scale;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Legge il peso corrente con il driver della bilancia ({@see \Modules\Weighing\Enums\ScaleDriverEnum::driverClass()}).
 * Rifiuta le bilance inattive e le letture oltre la portata. La verificazione metrologica si controlla
 * quando il peso si registra (Open/Close/RecordSingle), non alla lettura.
 *
 * @throws WeighingException
 */
class ReadScaleAction
{
    use QueueableAction;

    public function execute(Scale $scale): ScaleReadingData
    {
        if (! $scale->is_active) {
            throw ScaleUnavailableException::inactive($scale);
        }

        $driver = app($scale->driver->driverClass());
        Assert::isInstanceOf($driver, ScaleDriverContract::class);

        $reading = $driver->read($scale);

        if ($scale->maxCapacityKg() > 0 && $reading->weightKg > $scale->maxCapacityKg()) {
            throw ScaleUnavailableException::overload($scale, $reading->weightKg);
        }

        return $reading;
    }
}
