<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\ScaleResource\Pages;

use Modules\Weighing\Filament\Resources\ScaleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreateScale extends XotBaseCreateRecord
{
    protected static string $resource = ScaleResource::class;
}
