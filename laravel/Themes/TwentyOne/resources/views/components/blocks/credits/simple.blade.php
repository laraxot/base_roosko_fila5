{{-- Saldo crediti nella barra in alto: numero formattato, unità esplicita per i lettori di schermo,
     collegamento al portafoglio (prima un numero nudo come "11000" senza significato). --}}
@if(isset($profile))
    <a href="{{ url(app()->getLocale().'/pages/wallet') }}"
       class="flex min-h-11 items-center gap-1.5 rounded-lg px-2 text-sm font-semibold tabular-nums text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-800"
       aria-label="{{ __('pub_theme::headernav.credits_aria', ['credits' => number_format((float) $profile->credits, 0, ',', '.')]) }}">
        <x-filament::icon icon="predict-coin4" class="h-5 w-5 text-amber-600 dark:text-amber-400" aria-hidden="true" />
        <span>{{ number_format((float) $profile->credits, 0, ',', '.') }}</span>
    </a>
@endif
