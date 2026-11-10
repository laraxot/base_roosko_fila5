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
 * Header actions (abilita' `create` della WeighingPolicy): apri pesata (prima pesata) e pesata singola. Etichette e campi da
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
                ->authorize('create')
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
                    $data = self::normalizeActionData($data);
                    $direction = $data['direction'];
                    $direction = $direction instanceof WeighingDirectionEnum
                        ? $direction
                        : WeighingDirectionEnum::from(is_string($direction) ? $direction : WeighingDirectionEnum::INBOUND->value);

                    $weighing = $this->runWeighingAction(fn (): Weighing => app(OpenWeighingAction::class)->execute(
                        $direction,
                        SafeFloatCastAction::cast($data['first_kg']),
                        self::scaleFrom($data['scale_id']),
                        $data['vehicle_plate'],
                        null,
                        $data['raw_reading'],
                    ));

                    if ($weighing !== null) {
                        $this->redirect(WeighingResource::getUrl('view', ['record' => $weighing]));
                    }
                }),
            'record_single_weighing' => Action::make('record_single_weighing')
                ->authorize('create')
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
                    $data = self::normalizeActionData($data);
                    $weighing = $this->runWeighingAction(fn (): Weighing => app(RecordSingleWeighingAction::class)->execute(
                        SafeFloatCastAction::cast($data['net_kg']),
                        self::scaleFrom($data['scale_id']),
                        null,
                        $data['raw_reading'],
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
     * @param  int|string|null  $scaleId
     */
    private static function scaleFrom(int|string|null $scaleId): ?Scale
    {
        $id = SafeIntCastAction::cast($scaleId);

        return $id > 0 ? Scale::query()->find($id) : null;
    }

    /**
     * @param  array<mixed, mixed>  $data
     * @return array{
     *     direction: WeighingDirectionEnum|string|null,
     *     scale_id: int|string|null,
     *     vehicle_plate: string|null,
     *     first_kg: int|float|string|null,
     *     net_kg: int|float|string|null,
     *     raw_reading: string|null,
     * }
     */
    private static function normalizeActionData(array $data): array
    {
        $direction = array_key_exists('direction', $data) ? $data['direction'] : null;
        $scaleId = array_key_exists('scale_id', $data) ? $data['scale_id'] : null;
        $vehiclePlate = array_key_exists('vehicle_plate', $data) ? $data['vehicle_plate'] : null;
        $firstKg = array_key_exists('first_kg', $data) ? $data['first_kg'] : null;
        $netKg = array_key_exists('net_kg', $data) ? $data['net_kg'] : null;
        $rawReading = array_key_exists('raw_reading', $data) ? $data['raw_reading'] : null;

        return [
            'direction' => $direction instanceof WeighingDirectionEnum || is_string($direction)
                ? $direction
                : null,
            'scale_id' => is_int($scaleId) || is_string($scaleId)
                ? $scaleId
                : null,
            'vehicle_plate' => is_string($vehiclePlate) ? $vehiclePlate : null,
            'first_kg' => is_int($firstKg) || is_float($firstKg) || is_string($firstKg)
                ? $firstKg
                : null,
            'net_kg' => is_int($netKg) || is_float($netKg) || is_string($netKg)
                ? $netKg
                : null,
            'raw_reading' => is_string($rawReading) ? $rawReading : null,
        ];
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
