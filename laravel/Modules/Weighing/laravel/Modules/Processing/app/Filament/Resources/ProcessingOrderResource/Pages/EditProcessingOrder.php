<?php

declare(strict_types=1);

namespace Modules\Processing\Filament\Resources\ProcessingOrderResource\Pages;

use Filament\Actions\Action;
use Modules\Processing\Filament\Resources\ProcessingOrderResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

/**
 * Modifica dei soli dati di pianificazione (il form disabilita il resto; lo stato cambia solo con le header actions della View).
 */
class EditProcessingOrder extends XotBaseEditRecord
{
    protected static string $resource = ProcessingOrderResource::class;

    /**
     * @return array<string, Action>
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
