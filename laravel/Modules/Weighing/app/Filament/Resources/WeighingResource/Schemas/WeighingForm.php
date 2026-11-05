<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\WeighingResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Modules\Weighing\Enums\WeighingDirectionEnum;
use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Solo lettura: la Resource non ha create/edit (i ticket passano dalle Actions di dominio);
 * il form serve alla risoluzione Xot (`XotBaseResource::form()`).
 */
class WeighingForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'ticket_number' => TextInput::make('ticket_number')->disabled(),
            'direction' => Select::make('direction')->options(WeighingDirectionEnum::class)->disabled(),
            'status' => Select::make('status')->options(WeighingStatusEnum::class)->disabled(),
            'vehicle_plate' => TextInput::make('vehicle_plate')->disabled(),
            'gross_kg' => TextInput::make('gross_kg')->disabled(),
            'tare_kg' => TextInput::make('tare_kg')->disabled(),
            'net_kg' => TextInput::make('net_kg')->disabled(),
        ];
    }
}
