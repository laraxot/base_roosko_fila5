<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\ScaleResource\Pages;

use Modules\Weighing\Filament\Resources\ScaleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditScale extends XotBaseEditRecord
{
    protected static string $resource = ScaleResource::class;
}
