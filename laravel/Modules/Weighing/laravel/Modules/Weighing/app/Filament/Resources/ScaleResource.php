<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Modules\Weighing\Filament\Resources\ScaleResource\Pages\CreateScale;
use Modules\Weighing\Filament\Resources\ScaleResource\Pages\EditScale;
use Modules\Weighing\Filament\Resources\ScaleResource\Pages\ListScales;
use Modules\Weighing\Filament\Resources\ScaleResource\Pages\ViewScale;
use Modules\Weighing\Models\Scale;
use Modules\Xot\Filament\Resources\XotBaseResource;

class ScaleResource extends XotBaseResource
{
    protected static ?string $model = Scale::class;

    /**
     * @return array<string, class-string<RelationManager>>
     */
    #[\Override]
    public static function getRelations(): array
    {
        return [];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListScales::route('/'),
            'create' => CreateScale::route('/create'),
            'view' => ViewScale::route('/{record}'),
            'edit' => EditScale::route('/{record}/edit'),
        ];
    }
}
