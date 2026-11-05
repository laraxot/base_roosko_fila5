<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources;

use Filament\Resources\RelationManagers\RelationManager;
use Modules\Weighing\Filament\Resources\WeighingResource\Pages\ListWeighings;
use Modules\Weighing\Filament\Resources\WeighingResource\Pages\ViewWeighing;
use Modules\Weighing\Models\Weighing;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Ticket di pesata: niente create/edit generici, i ticket si aprono, chiudono e annullano solo con
 * le header actions che chiamano le Actions di dominio (numero ticket, lock, calcolo netto).
 */
class WeighingResource extends XotBaseResource
{
    protected static ?string $model = Weighing::class;

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
            'index' => ListWeighings::route('/'),
            'view' => ViewWeighing::route('/{record}'),
        ];
    }
}
