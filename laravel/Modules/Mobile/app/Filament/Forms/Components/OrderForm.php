<?php

declare(strict_types=1);

namespace Modules\Mobile\Filament\Forms\Components;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;

class OrderForm
{
    /**
     * @param array<string, mixed> $item
     * @return array<int, mixed>
     */
    public static function getForm(array $item): array
    {
        return [
            Grid::make()
                ->schema([
                    Section::make('Order Items')
                        ->schema([
                            Repeater::make('items')
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Product')
                                        ->required()
                                        ->options(fn () => []) // DIPENDENZA RIMOSSA (Mobile ⇸ Restaurant proibita)
                                        ->searchable()
                                        ->preload(),

                                    TextInput::make('quantity')
                                        ->label('Quantity')
                                        ->numeric()
                                        ->default(1)
                                        ->required(),

                                    TextInput::make('unit_price')
                                        ->label('Unit Price')
                                        ->numeric()
                                        ->required(),

                                    Textarea::make('notes')
                                        ->label('Notes'),

                                    Repeater::make('modifiers')
                                        ->label('Modifiers')
                                        ->schema([
                                            Select::make('modifier_id')
                                                ->label('Modifier')
                                                ->required()
                                                ->options(fn () => []) // DIPENDENZA RIMOSSA (Mobile ⇸ Restaurant proibita)
                                                ->searchable()
                                                ->preload(),

                                            TextInput::make('price_adjustment')
                                                ->label('Price Adjustment')
                                                ->numeric()
                                                ->default(0),
                                        ]),
                                ])
                                ->defaultItems(1)
                                ->reorderable(false),
                        ])
                        ->columnSpan(8),

                    Section::make('Order Details')
                        ->schema([
                            Select::make('waiter_session_id')
                                ->label('Waiter Session')
                                ->required()
                                ->options(fn () => \Modules\Mobile\Models\WaiterSession::query()
                                    ->pluck('name', 'id'))
                                ->searchable()
                                ->preload(),

                            Select::make('table_id')
                                ->label('Table')
                                ->required()
                                ->options(fn () => []) // DIPENDENZA RIMOSSA (Mobile ⇸ Restaurant proibita)
                                ->searchable()
                                ->preload(),

                            Textarea::make('notes')
                                ->label('General Notes'),

                            Hidden::make('shift_id'),

                            Hidden::make('source')
                                ->default('mobile'),
                        ])
                        ->columnSpan(4),
                ]),
        ];
    }
}
