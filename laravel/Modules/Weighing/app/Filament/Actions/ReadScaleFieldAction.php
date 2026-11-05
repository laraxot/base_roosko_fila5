<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Actions;

use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Modules\Weighing\Filament\Actions\Concerns\InteractsWithScaleReading;
use Modules\Weighing\Models\Scale;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Modules\Xot\Filament\Actions\XotBaseAction;

/**
 * Suffix action di un campo peso: legge la bilancia scelta nel campo fratello `scale_id` e scrive
 * il peso nel campo e la riga grezza nel campo fratello `raw_reading` (se presente, tipicamente Hidden).
 *
 * ```php
 * TextInput::make('first_kg')->numeric()->suffixAction(ReadScaleFieldAction::make()),
 * Hidden::make('raw_reading'),
 * ```
 *
 * Lang: Modules/Weighing/lang/{locale}/read_scale_field.php.
 */
class ReadScaleFieldAction extends XotBaseAction
{
    use InteractsWithScaleReading;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configureReadScale();
        $this
            ->iconButton()
            ->action(function (Get $get, Set $set, Component $component): void {
                $scaleId = SafeIntCastAction::cast($get('scale_id'));
                $scale = $scaleId > 0 ? Scale::query()->find($scaleId) : null;
                if ($scale === null) {
                    $this->notifyMissingScale();

                    return;
                }

                $reading = $this->readScaleAndNotify($scale);
                if ($reading === null) {
                    return;
                }

                $set($component, $reading->weightKg);
                $set('raw_reading', $reading->raw);
            });
    }

    private function notifyMissingScale(): void
    {
        Notification::make()
            ->title($this->transReadScale('missing_scale'))
            ->warning()
            ->send();
    }
}
