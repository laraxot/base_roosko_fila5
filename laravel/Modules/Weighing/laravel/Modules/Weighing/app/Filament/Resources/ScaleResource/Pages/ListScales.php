<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\ScaleResource\Pages;

use Modules\Weighing\Filament\Resources\ScaleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListScales extends XotBaseListRecords
{
    protected static string $resource = ScaleResource::class;
}
