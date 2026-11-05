<?php

declare(strict_types=1);

namespace Modules\Mobile\Filament\Pages\Mobile;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Modules\Mobile\Models\WaiterSession;
use Modules\Xot\Filament\Pages\XotBasePage;

class WaiterDashboard extends XotBasePage
{
    public static ?string $model = WaiterSession::class;

    public function getView(): string
    {
        return 'mobile::waiter-dashboard';
    }

    public function getFormSchema(): array
    {
        return [
            Section::make('Waiter Information')
                ->schema([
                    TextInput::make('device_name')
                        ->label('Device Name')
                        ->required(),
                    TextInput::make('platform')
                        ->label('Platform')
                        ->required(),
                ])
                ->columns(2),

            Section::make('Location')
                ->schema([
                    TextInput::make('location_lat')
                        ->label('Latitude'),
                    TextInput::make('location_lng')
                        ->label('Longitude'),
                ])
                ->columns(2),
        ];
    }
}
