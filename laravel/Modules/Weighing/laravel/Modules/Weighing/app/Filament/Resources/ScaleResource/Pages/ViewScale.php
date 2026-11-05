<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\ScaleResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Modules\Weighing\Filament\Actions\ReadScaleHeaderAction;
use Modules\Weighing\Filament\Resources\ScaleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewScale extends XotBaseViewRecord
{
    protected static string $resource = ScaleResource::class;

    /**
     * @return array<string, Action|ActionGroup>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            'read_scale' => ReadScaleHeaderAction::make(),
            'edit' => EditAction::make(),
        ];
    }
}
