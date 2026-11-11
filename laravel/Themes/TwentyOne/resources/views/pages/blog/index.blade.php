<?php

declare(strict_types=1);

use Modules\Blog\Models\Article;
use Illuminate\View\View;
use Modules\Predict\Models\Predict;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('blog.index');

/*
 * Elenco articoli del blog (notizie, guide). Esclude i mercati, che sono anch'essi `articles`.
 * Sostituisce la pagina d'esempio "Welcome to your new blog" (layout marketing, testi in inglese).
 */
render(static fn (View $view): View => $view->with('posts', Article::query()
    ->where(static fn ($query) => $query->whereNull('type')->orWhere('type', '!=', Predict::class))
    ->whereNotNull('published_at')
    ->where('published_at', '<=', now())
    ->orderByDesc('published_at')
    ->limit(30)
    ->get()));
?>
<x-layouts.app>
    <div class="mx-auto w-full max-w-4xl px-4 py-10 sm:px-6">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">{{ __('pub_theme::blog.title') }}</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('pub_theme::blog.subtitle') }}</p>

        <div class="mt-8 space-y-6">
            @forelse ($posts as $post)
                <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->published_at?->translatedFormat('j F Y') }}</p>
                    <h2 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">
                        <a href="{{ url(app()->getLocale().'/articles/'.$post->slug) }}" class="hover:underline">{{ $post->title }}</a>
                    </h2>
                    @if (filled($post->description))
                        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ \Illuminate\Support\Str::limit(strip_tags((string) $post->description), 220) }}</p>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center text-gray-600 dark:border-gray-600 dark:text-gray-400">
                    <p>{{ __('pub_theme::blog.empty') }}</p>
                    <a href="{{ url(app()->getLocale().'/predicts') }}" class="mt-4 inline-flex rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">{{ __('pub_theme::blog.go_to_markets') }}</a>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
