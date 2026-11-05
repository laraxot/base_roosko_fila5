<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Actions;

use Illuminate\Database\Eloquent\Model;
use Modules\Weighing\Filament\Actions\Concerns\InteractsWithScaleReading;
use Modules\Weighing\Models\Scale;
use Modules\Weighing\Models\Weighing;
use Modules\Xot\Filament\Actions\XotBaseAction;

/**
 * Header action "Leggi bilancia" per le pagine di una Scale o di una Weighing (usa la sua bilancia):
 * mostra il peso corrente e se e' stabile, senza registrare nulla.
 *
 * Lang: Modules/Weighing/lang/{locale}/read_scale_header.php.
 */
class ReadScaleHeaderAction extends XotBaseAction
{
    use InteractsWithScaleReading;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configureReadScale();
        $this
            ->button()
            ->visible(static fn (?Model $record): bool => self::scaleOf($record)?->driver->canRead() ?? false)
            ->action(function (?Model $record): void {
                $scale = self::scaleOf($record);
                if ($scale !== null) {
                    $this->readScaleAndNotify($scale);
                }
            });
    }

    private static function scaleOf(?Model $record): ?Scale
    {
        return match (true) {
            $record instanceof Scale => $record,
            $record instanceof Weighing => $record->scale,
            default => null,
        };
    }
}
