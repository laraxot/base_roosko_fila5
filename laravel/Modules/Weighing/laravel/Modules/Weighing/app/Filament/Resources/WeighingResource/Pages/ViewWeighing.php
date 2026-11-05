<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\WeighingResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Modules\Weighing\Actions\CloseWeighingAction;
use Modules\Weighing\Actions\VoidWeighingAction;
use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Weighing\Exceptions\WeighingException;
use Modules\Weighing\Filament\Actions\ReadScaleFieldAction;
use Modules\Weighing\Filament\Actions\ReadScaleHeaderAction;
use Modules\Weighing\Filament\Resources\WeighingResource;
use Modules\Weighing\Models\Weighing;
use Modules\Xot\Actions\Cast\SafeFloatCastAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

/**
 * Header actions: leggi bilancia, chiudi (seconda pesata), annulla. Etichette e campi da
 * Modules/Weighing/lang/{locale}/weighing.php.
 */
class ViewWeighing extends XotBaseViewRecord
{
    protected static string $resource = WeighingResource::class;

    /**
     * @return array<string, Action|ActionGroup>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            'read_scale' => ReadScaleHeaderAction::make()
                ->visible(static fn (Weighing $record): bool => $record->status === WeighingStatusEnum::OPEN
                    && ($record->scale?->driver->canRead() ?? false)),
            'close_weighing' => Action::make('close_weighing')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(static fn (Weighing $record): bool => $record->status === WeighingStatusEnum::OPEN)
                ->fillForm(static fn (Weighing $record): array => ['scale_id' => $record->scale_id])
                ->schema([
                    'scale_id' => Hidden::make('scale_id'),
                    'second_kg' => TextInput::make('second_kg')
                        ->numeric()
                        ->minValue(0.001)
                        ->required()
                        ->suffixAction(ReadScaleFieldAction::make()),
                    'raw_reading' => Hidden::make('raw_reading'),
                ])
                ->action(static function (Weighing $record, array $data): void {
                    try {
                        app(CloseWeighingAction::class)->execute(
                            $record,
                            SafeFloatCastAction::cast($data['second_kg'] ?? null),
                            is_string($data['raw_reading'] ?? null) ? $data['raw_reading'] : null,
                        );
                    } catch (WeighingException|\InvalidArgumentException $e) {
                        Notification::make()->title($e->getMessage())->danger()->send();
                    }
                }),
            'void_weighing' => Action::make('void_weighing')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(static fn (Weighing $record): bool => $record->status !== WeighingStatusEnum::VOIDED)
                ->schema([
                    'void_reason' => Textarea::make('void_reason')->required()->maxLength(1000),
                ])
                ->action(static function (Weighing $record, array $data): void {
                    try {
                        app(VoidWeighingAction::class)->execute(
                            $record,
                            is_string($data['void_reason'] ?? null) ? $data['void_reason'] : '',
                        );
                    } catch (WeighingException|\InvalidArgumentException $e) {
                        Notification::make()->title($e->getMessage())->danger()->send();
                    }
                }),
        ];
    }
}
