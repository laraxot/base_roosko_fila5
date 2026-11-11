<?php

declare(strict_types=1);

use Illuminate\View\View;
use Modules\Predict\Models\Category;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('category.view');

/*
 * Pagina di una categoria: titolo, sottocategorie e i mercati della categoria (con le sottocategorie).
 * Prima usava un include deprecato che mostrava "Nessun mercato disponibile" e un avviso
 * "Deprecation Notice" in fondo alla pagina.
 */
render(static function (View $view, string $slug): View {
    $category = Category::query()->where('slug', $slug)->first();
    abort_if($category === null, 404);

    return $view->with([
        'category' => $category,
        'parent' => $category->parent_id !== null ? Category::query()->find($category->parent_id) : null,
        'children' => Category::query()->where('parent_id', $category->getKey())->orderBy('id')->get(),
    ]);
});
?>
<x-layouts.app>
    <div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6">
        <nav class="text-sm text-gray-500 dark:text-gray-400" aria-label="breadcrumb">
            <a href="{{ url(app()->getLocale().'/categories') }}" class="hover:underline">{{ __('pub_theme::categories.all') }}</a>
            @if ($parent)
                <span class="mx-1">›</span>
                <a href="{{ url(app()->getLocale().'/categories/'.$parent->slug) }}" class="hover:underline">{{ $parent->title }}</a>
            @endif
        </nav>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $category->title }}</h1>

        @if ($children->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($children as $child)
                    <a href="{{ url(app()->getLocale().'/categories/'.$child->slug) }}"
                       class="inline-flex min-h-11 items-center rounded-full border border-gray-300 px-4 text-sm font-medium text-gray-800 hover:border-blue-600 hover:text-blue-700 dark:border-gray-600 dark:text-gray-200">
                        {{ $child->title }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="mt-6">
            @livewire(\Modules\Predict\Filament\Widgets\PredictTableWidget::class, ['category' => $category->slug], key('category-'.$category->slug))
        </div>
    </div>
</x-layouts.app>
