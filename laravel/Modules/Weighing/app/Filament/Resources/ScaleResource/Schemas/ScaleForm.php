<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\ScaleResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Modules\Weighing\Enums\ScaleDriverEnum;
use Modules\Weighing\Enums\ScaleTypeEnum;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Lang: Modules/Weighing/lang/{locale}/scale_form.php.
 */
class ScaleForm extends XotBaseResourceForm
{
    public function getFormColumns(): int
    {
        return 2;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'code' => TextInput::make('code')
                ->required()
                ->maxLength(64)
                ->unique(ignoreRecord: true),
            'name' => TextInput::make('name')
                ->required()
                ->maxLength(255),
            'type' => Select::make('type')
                ->options(ScaleTypeEnum::class)
                ->required(),
            'driver' => Select::make('driver')
                ->options(ScaleDriverEnum::class)
                ->required()
                ->live(),
            'host' => TextInput::make('host')
                ->maxLength(255)
                ->visible(static fn (Get $get): bool => self::driver($get)?->requiresHost() ?? false)
                ->required(static fn (Get $get): bool => self::driver($get)?->requiresHost() ?? false),
            'port' => TextInput::make('port')
                ->integer()
                ->minValue(1)
                ->maxValue(65535)
                ->visible(static fn (Get $get): bool => self::driver($get)?->requiresHost() ?? false),
            'max_capacity_kg' => TextInput::make('max_capacity_kg')
                ->numeric()
                ->minValue(0.001)
                ->required(),
            'resolution_kg' => TextInput::make('resolution_kg')
                ->numeric()
                ->minValue(0.001)
                ->required(),
            'verified_until' => DatePicker::make('verified_until'),
            'is_active' => Toggle::make('is_active')
                ->default(true),
        ];
    }

    private static function driver(Get $get): ?ScaleDriverEnum
    {
        $state = $get('driver');

        return $state instanceof ScaleDriverEnum
            ? $state
            : ScaleDriverEnum::tryFrom(is_string($state) ? $state : '');
    }
}
