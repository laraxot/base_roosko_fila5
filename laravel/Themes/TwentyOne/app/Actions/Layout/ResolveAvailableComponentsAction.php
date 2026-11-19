<?php

declare(strict_types=1);

namespace Themes\TwentyOne\Actions\Layout;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

/**
 * Filtra un elenco di componenti Blade `namespace::nome` tenendo solo quelli registrati.
 *
 * Il tema e' condiviso da piu' progetti: un componente di un modulo (es. `predict::daily-bonus`)
 * esiste solo dove c'e' quel modulo. Scritto direttamente come `<x-predict::...>` il layout non
 * compila senza il modulo (Blade risolve i componenti in compilazione); passando da qui e da
 * `<x-dynamic-component>` il componente compare dove c'e' e sparisce dove non c'e'.
 */
final class ResolveAvailableComponentsAction
{
    use QueueableAction;

    /**
     * @param  list<string>  $components  nomi `namespace::nome-componente`
     * @return list<string>
     */
    public function execute(array $components): array
    {
        return array_values(array_filter($components, $this->exists(...)));
    }

    private function exists(string $component): bool
    {
        if (! str_contains($component, '::')) {
            return view()->exists('components.'.$component);
        }

        [$namespace, $name] = explode('::', $component, 2);

        if (view()->exists($namespace.'::components.'.$name)) {
            return true;
        }

        $classNamespace = Blade::getClassComponentNamespaces()[$namespace] ?? null;
        if (! is_string($classNamespace)) {
            return false;
        }

        $class = $classNamespace.'\\'.implode('\\', array_map(
            static fn (string $part): string => Str::studly($part),
            explode('.', $name),
        ));

        return class_exists($class);
    }
}
