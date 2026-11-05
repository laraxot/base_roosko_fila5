<?php

declare(strict_types=1);

namespace Modules\Roosko\Filament\Actions\Concerns;

use Closure;
use Filament\Notifications\Notification;
use DomainException;
use InvalidArgumentException;
use Modules\Weighing\Exceptions\WeighingException;

/**
 * Esegue la action di dominio e mostra l'errore all'operatore senza chiudere il modale.
 * Le eccezioni attese sono quelle di dominio (RooskoException e le DomainException degli altri
 * moduli) e le Assert (InvalidArgumentException); le altre (database, rete) risalgono.
 */
trait NotifiesDomainErrors
{
    /**
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
     * `$successTitle` puo' dipendere dal risultato della action (es. il codice dell'unita' creata).
     *
     * @template TResult
     *
     * @param  Closure(): TResult  $callback
     * @param  string|Closure(TResult): string  $successTitle
     */
    protected function runDomainAction(Closure $callback, string|Closure $successTitle): void
    {
        try {
            $result = $callback();
<<<<<<< HEAD
=======
     * @param  Closure(): mixed  $callback
     */
    protected function runDomainAction(Closure $callback, string $successTitle): void
    {
        try {
            $callback();
>>>>>>> laraxot/dev
=======
>>>>>>> laraxot/dev
        } catch (DomainException|InvalidArgumentException|WeighingException $exception) {
            Notification::make()
                ->danger()
                ->title(static::transAction('error'))
                ->body($exception->getMessage())
                ->send();

            $this->halt();
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev

            return;
        }

        Notification::make()
            ->success()
            ->title($successTitle instanceof Closure ? $successTitle($result) : $successTitle)
            ->send();
<<<<<<< HEAD
=======
        }

        Notification::make()->success()->title($successTitle)->send();
>>>>>>> laraxot/dev
=======
>>>>>>> laraxot/dev
    }
}
