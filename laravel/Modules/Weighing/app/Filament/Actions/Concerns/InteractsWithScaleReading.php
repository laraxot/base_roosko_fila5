<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Actions\Concerns;

use Filament\Notifications\Notification;
use Modules\Weighing\Actions\ReadScaleAction;
use Modules\Weighing\Datas\ScaleReadingData;
use Modules\Weighing\Exceptions\WeighingException;
use Modules\Weighing\Models\Scale;
use Modules\Xot\Actions\GetTransKeyAction;

/**
 * Comune alle azioni "Leggi bilancia". Testi dal file lang dell'azione
 * (`weighing::read_scale_header` / `weighing::read_scale_field`, chiave `actions.read_scale.*`):
 * le azioni sono riusabili da altri moduli (Roosko) e devono presentarsi uguali ovunque.
 */
trait InteractsWithScaleReading
{
    public static function getDefaultName(): ?string
    {
        return 'read_scale';
    }

    protected function configureReadScale(): void
    {
        $this
            ->icon('heroicon-o-scale')
            ->color('info')
            ->label(fn (): string => $this->transReadScale('label'))
            ->tooltip(fn (): string => $this->transReadScale('tooltip'));
    }

    /**
     * Legge la bilancia e notifica l'esito; null (con notifica di errore) se la lettura fallisce.
     */
    protected function readScaleAndNotify(Scale $scale): ?ScaleReadingData
    {
        try {
            $reading = app(ReadScaleAction::class)->execute($scale);
        } catch (WeighingException $e) {
            Notification::make()
                ->title($this->transReadScale('error'))
                ->body($e->getMessage())
                ->danger()
                ->send();

            return null;
        }

        $notification = Notification::make()
            ->title($this->transReadScale($reading->stable ? 'success' : 'unstable', [
                'scale' => $scale->code,
                'weight' => number_format($reading->weightKg, 3, ',', '.'),
            ]))
            ->body($reading->raw);

        $reading->stable ? $notification->success() : $notification->warning();
        $notification->send();

        return $reading;
    }

    /**
     * @param  array<string, string>  $replace
     */
    protected function transReadScale(string $key, array $replace = []): string
    {
        $fullKey = app(GetTransKeyAction::class)->execute(static::class).'.actions.read_scale.'.$key;
        $translated = __($fullKey, $replace);

        return is_string($translated) && $translated !== $fullKey ? $translated : $key;
    }
}
