<?php

declare(strict_types=1);

namespace Modules\Mobile\Filament\Resources\Mobile;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Mobile\Models\OrderQueue;
use Modules\Xot\Filament\Resources\XotBaseResource;

class OrderResource extends XotBaseResource
{
    protected static ?string $model = OrderQueue::class;

    public static function getNavigationBadge(): ?string
    {
        return (string) OrderQueue::where('status', OrderQueue::STATUS_PENDING)->count();
    }

    public static function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    public static function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    public static function getRelations(): array
    {
        return [];
    }
}
