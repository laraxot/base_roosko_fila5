<?php

declare(strict_types=1);

namespace Modules\Labeling\Contracts;

use Modules\Labeling\Datas\PrinterDeliveryData;
use Modules\Labeling\Enums\PrinterDriverEnum;
use Modules\Labeling\Exceptions\LabelingException;
use Modules\Labeling\Models\LabelPrint;

/**
 * Consegna lo snapshot gia' renderizzato di una stampa (`label_prints.rendered`) al dispositivo
 * o al file. Il driver si sceglie con {@see PrinterDriverEnum::driverClass()}.
 */
interface PrinterDriverContract
{
    /**
     * @throws LabelingException
     */
    public function deliver(LabelPrint $print): PrinterDeliveryData;
}
