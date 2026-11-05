<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\WeighingResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Modules\Weighing\Enums\WeighingDirectionEnum;
use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Lang: Modules/Weighing/lang/{locale}/weighings.php.
 */
class WeighingsTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'ticket_number' => TextColumn::make('ticket_number')->searchable()->sortable(),
            'direction' => TextColumn::make('direction')->badge()->sortable(),
            'status' => TextColumn::make('status')->badge()->sortable(),
            'vehicle_plate' => TextColumn::make('vehicle_plate')->searchable(),
            'scale' => TextColumn::make('scale.code'),
            'gross_kg' => TextColumn::make('gross_kg')->numeric(0)->suffix(' kg'),
            'tare_kg' => TextColumn::make('tare_kg')->numeric(0)->suffix(' kg'),
            'net_kg' => TextColumn::make('net_kg')->numeric(0)->suffix(' kg')->sortable(),
            'weighable_type' => TextColumn::make('weighable_type')
                ->formatStateUsing(static fn (?string $state): string => $state === null ? '—' : class_basename($state))
                ->toggleable(isToggledHiddenByDefault: true),
            'gross_weighed_at' => TextColumn::make('gross_weighed_at')->dateTime()->sortable(),
            'closed_at' => TextColumn::make('closed_at')->dateTime()->sortable(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'direction' => SelectFilter::make('direction')->options(WeighingDirectionEnum::class),
            'status' => SelectFilter::make('status')->options(WeighingStatusEnum::class),
            'scale_id' => SelectFilter::make('scale_id')->relationship('scale', 'name'),
        ];
    }

    /**
     * @return array<string, Action|ActionGroup>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make()->iconButton(),
        ];
    }

    /**
     * I ticket non si cancellano: niente azioni di gruppo.
     *
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'id';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}
