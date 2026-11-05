<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\WeighingResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Modules\Weighing\Actions\OpenWeighingAction;
use Modules\Weighing\Actions\RecordSingleWeighingAction;
use Modules\Weighing\Enums\WeighingDirectionEnum;
use Modules\Weighing\Exceptions\WeighingException;
use Modules\Weighing\Filament\Actions\ReadScaleFieldAction;
use Modules\Weighing\Filament\Resources\WeighingResource;
use Modules\Weighing\Models\Scale;
use Modules\Weighing\Models\Weighing;
use Modules\Xot\Actions\Cast\SafeFloatCastAction;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

/**
 * Header actions: apri pesata (prima pesata) e pesata singola. Etichette e campi da
 * Modules/Weighing/lang/{locale}/weighing.php.
 */
class ListWeighings extends XotBaseListRecords
{
    protected static string $resource = WeighingResource::class;

    /**
     * @return array<string, Action>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            'open_weighing' => Action::make('open_weighing')
                ->icon('heroicon-o-arrow-right-end-on-rectangle')
                ->color('primary')
                ->fillForm(['direction' => WeighingDirectionEnum::INBOUND->value])
                ->schema([
                    'direction' => Select::make('direction')
                        ->options(WeighingDirectionEnum::class)
                        ->required(),
                    'scale_id' => self::scaleSelect(),
                    'vehicle_plate' => TextInput::make('vehicle_plate')->maxLength(20),
                    'first_kg' => TextInput::make('first_kg')
                        ->numeric()
                        ->minValue(0.001)
                        ->required()
                        ->suffixAction(ReadScaleFieldAction::make()),
                    'raw_reading' => Hidden::make('raw_reading'),
                ])
                ->action(function (array $data): void {
                    $direction = $data['direction'] ?? null;
                    $direction = $direction instanceof WeighingDirectionEnum
                        ? $direction
                        : WeighingDirectionEnum::from(is_string($direction) ? $direction : WeighingDirectionEnum::INBOUND->value);

                    $weighing = $this->runWeighingAction(fn (): Weighing => app(OpenWeighingAction::class)->execute(
                        $direction,
                        SafeFloatCastAction::cast($data['first_kg'] ?? null),
                        self::scaleFrom($data),
                        is_string($data['vehicle_plate'] ?? null) ? $data['vehicle_plate'] : null,
                        null,
                        is_string($data['raw_reading'] ?? null) ? $data['raw_reading'] : null,
                    ));

                    if ($weighing !== null) {
                        $this->redirect(WeighingResource::getUrl('view', ['record' => $weighing]));
                    }
                }),
            'record_single_weighing' => Action::make('record_single_weighing')
                ->icon('heroicon-o-scale')
                ->color('gray')
                ->schema([
                    'scale_id' => self::scaleSelect(),
                    'net_kg' => TextInput::make('net_kg')
                        ->numeric()
                        ->minValue(0.001)
                        ->required()
                        ->suffixAction(ReadScaleFieldAction::make()),
                    'raw_reading' => Hidden::make('raw_reading'),
                ])
                ->action(function (array $data): void {
                    $weighing = $this->runWeighingAction(fn (): Weighing => app(RecordSingleWeighingAction::class)->execute(
                        SafeFloatCastAction::cast($data['net_kg'] ?? null),
                        self::scaleFrom($data),
                        null,
                        is_string($data['raw_reading'] ?? null) ? $data['raw_reading'] : null,
                    ));

                    if ($weighing !== null) {
                        $this->redirect(WeighingResource::getUrl('view', ['record' => $weighing]));
                    }
                }),
        ];
    }

    private static function scaleSelect(): Select
    {
        return Select::make('scale_id')
            ->options(static fn (): array => Scale::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->all())
            ->live();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function scaleFrom(array $data): ?Scale
    {
        $id = SafeIntCastAction::cast($data['scale_id'] ?? null);

        return $id > 0 ? Scale::query()->find($id) : null;
    }

    /**
     * Esegue l'Action di dominio e trasforma gli errori in notifica.
     *
     * @param  \Closure(): Weighing  $callback
     */
    private function runWeighingAction(\Closure $callback): ?Weighing
    {
        try {
            return $callback();
        } catch (WeighingException|\InvalidArgumentException $e) {
            Notification::make()->title($e->getMessage())->danger()->send();

            return null;
        }
    }
}
