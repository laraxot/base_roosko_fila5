<?php

declare(strict_types=1);

namespace Modules\Weighing\Contracts;

use Modules\Weighing\Datas\ScaleReadingData;
use Modules\Weighing\Enums\ScaleDriverEnum;
use Modules\Weighing\Exceptions\WeighingException;
use Modules\Weighing\Models\Scale;

/**
 * Lettura di una bilancia. Il driver si sceglie con {@see ScaleDriverEnum::driverClass()}.
 */
interface ScaleDriverContract
{
    /**
     * @throws WeighingException
     */
    public function read(Scale $scale): ScaleReadingData;
}
