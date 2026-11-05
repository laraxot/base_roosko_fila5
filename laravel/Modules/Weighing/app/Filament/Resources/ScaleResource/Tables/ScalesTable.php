<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\ScaleResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Modules\Weighing\Enums\ScaleDriverEnum;
use Modules\Weighing\Enums\ScaleTypeEnum;
use Modules\Weighing\Models\Scale;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Lang: Modules/Weighing/lang/{locale}/scales.php.
 */
class ScalesTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')->searchable()->sortable(),
            'name' => TextColumn::make('name')->searchable()->sortable(),
            'type' => TextColumn::make('type')->badge()->sortable(),
            'driver' => TextColumn::make('driver')->badge(),
            'host' => TextColumn::make('host')->toggleable(isToggledHiddenByDefault: true),
            'max_capacity_kg' => TextColumn::make('max_capacity_kg')->numeric(0)->suffix(' kg')->sortable(),
            'resolution_kg' => TextColumn::make('resolution_kg')->numeric(3)->suffix(' kg'),
            'verified_until' => TextColumn::make('verified_until')
                ->date()
                ->sortable()
                ->color(static fn (Scale $record): string => $record->isVerifiedAt(now()->toImmutable()) ? 'success' : 'danger'),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'type' => SelectFilter::make('type')->options(ScaleTypeEnum::class),
            'driver' => SelectFilter::make('driver')->options(ScaleDriverEnum::class),
            'is_active' => TernaryFilter::make('is_active'),
        ];
    }
}
