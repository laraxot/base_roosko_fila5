<?php

declare(strict_types=1);

namespace Modules\Mobile\Filament\Resources\Mobile;

// RIMOSSO: dipendenza Mobile -> Restaurant proibita
use Modules\Mobile\Models\OrderQueue;
use Modules\Xot\Filament\Resources\XotBaseResource;

class TableResource extends XotBaseResource
{
    protected static ?string $model = OrderQueue::class;

    public static function getRelations(): array
    {
        return [];
    }
}
