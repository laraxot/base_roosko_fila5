{{--
  Tre fatti verificabili sotto l'intestazione della home, ognuno collegato alla pagina che lo spiega.
  Prima: "Credits Virtuali / Trasparenza Totale / Governance Community" in inglese, verso pagine inesistenti (404).
--}}
@php
    $locale = app()->getLocale();
    $items = [
        ['icon' => 'heroicon-o-gift', 'label' => __('pub_theme::home.trust.free'), 'url' => url($locale.'/how-it-works')],
        ['icon' => 'heroicon-o-scale', 'label' => __('pub_theme::home.trust.rules'), 'url' => url($locale.'/terms')],
        ['icon' => 'heroicon-o-identification', 'label' => __('pub_theme::home.trust.adults'), 'url' => url($locale.'/terms')],
    ];
@endphp
<nav class="border-b border-white/10 bg-slate-900" aria-label="{{ __('pub_theme::home.trust.aria') }}">
    <ul class="mx-auto flex max-w-7xl flex-wrap items-center justify-center gap-x-8 gap-y-2 px-4 py-3 sm:px-6 lg:px-8">
        @foreach ($items as $item)
            <li>
                <a href="{{ $item['url'] }}" class="inline-flex min-h-11 items-center gap-2 rounded-lg px-2 text-sm font-medium text-slate-200 transition-colors duration-150 hover:text-white hover:underline">
                    <x-filament::icon :icon="$item['icon']" class="h-5 w-5 text-sky-300" aria-hidden="true" />
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
