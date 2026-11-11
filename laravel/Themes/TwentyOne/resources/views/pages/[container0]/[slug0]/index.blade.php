<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Actions\ResolvePageAction;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Modules\Cms\Models\Page;

name('container0.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';

    public string $slug0 = '';

    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0, string $slug0 = ''): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;

        $resolved = app(ResolvePageAction::class)->execute($container0, $slug0);

        $this->pageSlug = $resolved->pageSlug;

        // Pagina inesistente o oggetto non trovato (es. /predicts/titolo-sbagliato): 404 vero.
        // Prima usciva una pagina vuota o "Mercato non trovato." con stato 200.
        if (Page::findUniqueBySlug($resolved->pageSlug) === null
            || ($resolved->item === null && $resolved->pageSlug === $container0.'.view')) {
            abort(404);
        }

        $item = $resolved->item;
        $predictId = is_object($item) && isset($item->id) ? $item->id : null;

        $this->data = [
            'container0' => $container0,
            'slug0' => $slug0,
            'slug' => $slug0,
            'item' => $item,
            'predictId' => $predictId,
        ];
    }
};
?>
@php
    // Titolo e descrizione della scheda/anteprima social: quelli dell'oggetto (es. il mercato),
    // non il titolo generico della pagina CMS. Prima ogni mercato condivisa usciva come "Predict".
    $seoItem = app(\Modules\Cms\Actions\ResolvePageAction::class)->execute((string) ($container0 ?? ''), (string) ($slug0 ?? ''))->item;
    $seoTitle = is_object($seoItem) && is_string($seoItem->title ?? null) ? $seoItem->title : null;
    $seoDescription = is_object($seoItem) && is_string($seoItem->description ?? null)
        ? \Illuminate\Support\Str::limit(trim(strip_tags($seoItem->description)), 160)
        : null;
@endphp

<x-layouts.app :title="$seoTitle" :description="$seoDescription">
    @volt('container0.view')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
