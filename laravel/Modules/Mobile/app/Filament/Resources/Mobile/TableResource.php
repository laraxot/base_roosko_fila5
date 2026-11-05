<?php

declare(strict_types=1);

namespace Modules\Mobile\Filament\Resources\Mobile;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
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
