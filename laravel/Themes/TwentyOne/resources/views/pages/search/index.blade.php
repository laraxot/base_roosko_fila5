<?php

declare(strict_types=1);

use Illuminate\View\View;
use Modules\Predict\Models\Predict;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('search');

/*
 * Sta in search/index.blade.php e non in search.blade.php: Folio prova [container0]/index prima dei file
 * singoli, quindi /it/search finiva sulla pagina CMS (404).
 * Ricerca dei mercati per parola del titolo (link "Cerca" della barra in basso su telefono).
 * Sostituisce la pagina d'esempio in inglese, che rispondeva 404.
 */
render(static function (View $view): View {
    $query = trim((string) request()->query('q', request()->query('search', '')));
    $results = $query === ''
        ? collect()
        : Predict::query()
            ->where('title', 'like', '%'.str_replace(['%', '_'], ['\%', '\_'], $query).'%')
            ->orderByDesc('published_at')
            ->limit(30)
            ->get();

    return $view->with(['query' => $query, 'results' => $results]);
});
?>
<x-layouts.app>
    <div class="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('pub_theme::search.title') }}</h1>

        <form method="get" class="mt-6 flex gap-2" role="search">
            <label for="q" class="sr-only">{{ __('pub_theme::search.label') }}</label>
            <input id="q" name="q" type="search" value="{{ $query }}" placeholder="{{ __('pub_theme::search.placeholder') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white" />
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">
                <x-heroicon-o-magnifying-glass class="size-5" />
                <span>{{ __('pub_theme::search.submit') }}</span>
            </button>
        </form>

        @if ($query !== '')
            <p class="mt-6 text-sm text-gray-600 dark:text-gray-400">{{ trans_choice('pub_theme::search.count', $results->count(), ['count' => $results->count(), 'query' => $query]) }}</p>
            <ul class="mt-4 space-y-3">
                @foreach ($results as $market)
                    <li>
                        <a href="{{ url(app()->getLocale().'/predicts/'.$market->slug) }}"
                           class="block rounded-xl border border-gray-200 bg-white p-4 hover:border-blue-500 dark:border-gray-700 dark:bg-gray-800">
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $market->title }}</span>
                            @if ($market->closed_at)
                                <span class="mt-1 block text-sm text-gray-500 dark:text-gray-400">{{ __('pub_theme::search.closes', ['date' => $market->closed_at->translatedFormat('j F Y')]) }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-layouts.app>
