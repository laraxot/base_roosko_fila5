<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Modules\Cms\Models\Page;

name('container0.index');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';
    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0): void
    {
        $this->container0 = $container0;
        // Le lingue supportate sono prefissi URL della home CMS (slug 'home'),
        // non container CMS '{locale}.index'.
        $locales = LaravelLocalization::getSupportedLanguagesKeys();
        if (in_array($container0, is_array($locales) ? $locales : [], true)) {
            $this->pageSlug = 'home';
        } else {
            // Prima si cercava solo '{container0}.index': le pagine semplici (about, terms, privacy…)
            // hanno slug '{container0}' e uscivano vuote con stato 200. Ora: '{c}.index', poi '{c}',
            // altrimenti 404 vero (pagina d'errore e stato 404, utile anche ai motori di ricerca).
            $this->pageSlug = collect([$container0.'.index', $container0])
                ->first(static fn (string $slug): bool => Page::findUniqueBySlug($slug) !== null)
                ?? abort(404);
        }
        $this->data = ['container0' => $container0];
    }
};
?>

<x-layouts.app>
    @volt('container0.index')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
