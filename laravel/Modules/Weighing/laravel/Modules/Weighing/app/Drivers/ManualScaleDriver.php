<?php

declare(strict_types=1);

namespace Modules\Weighing\Drivers;

use Modules\Weighing\Contracts\ScaleDriverContract;
use Modules\Weighing\Datas\ScaleReadingData;
use Modules\Weighing\Exceptions\ScaleUnavailableException;
use Modules\Weighing\Models\Scale;

/**
 * Bilancia senza collegamento: l'operatore legge il display e digita il peso, non c'e' niente da leggere.
 */
class ManualScaleDriver implements ScaleDriverContract
{
    public function read(Scale $scale): ScaleReadingData
    {
        throw ScaleUnavailableException::manual($scale);
    }
}
