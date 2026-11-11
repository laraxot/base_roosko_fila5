@php
    // Support multiple data sources from CMS
    $record = $record ?? $predict ?? $item ?? $article ?? null;
    
    // Try to get from $data array (CMS standard)
    if (!$record && isset($data['record'])) {
        $record = $data['record'];
    }

    if (!$record && isset($data['predict'])) {
        $record = $data['predict'];
    }

    if (!$record && isset($data['item'])) {
        $record = $data['item'];
    }
    
    // Try to get from page attribute
    if (!$record && isset($data['article'])) {
        $record = $data['article'];
    }
    
    // Build marketData if not provided
    $marketData = $marketData ?? $data['marketData'] ?? [];
    
    if (empty($marketData) && $record) {
        $marketData = [
            'current_prices' => [
                'yes' => $record->value_buy ?? 67,
                'no' => $record->value_sell ?? 33,
            ],
            'volume_24h' => $record->volume_24h ?? 24580,
            'total_volume' => $record->total_volume ?? ($record->volume_24h ?? 24580) * 1.5,
            'participants' => $record->participants_count ?? 1245,
            'open_interest' => $record->open_interest ?? ($record->volume_24h ?? 24580) * 0.75,
            'spread' => abs(($record->value_buy ?? 67) - ($record->value_sell ?? 33)),
            'liquidity' => $record->liquidity ?? 'high',
            'last_updated' => now(),
        ];
    }

    $tx = static function (string $key, string $fallback): string {
        $translated = __($key);
        if (is_string($translated) && $translated !== $key) {
            return $translated;
        }

        $labelKey = $key . '.label';
        $translatedLabel = __($labelKey);
        if (is_string($translatedLabel) && $translatedLabel !== $labelKey) {
            return $translatedLabel;
        }

        return $fallback;
    };
@endphp

@if($record)
@php
    $locale = app()->getLocale();
    $pick = static fn ($value): string => is_array($value) ? (string) ($value[$locale] ?? $value['it'] ?? $value['en'] ?? reset($value) ?? '') : (string) $value;
    $title = $pick($record->title ?? '');
    $description = $pick($record->description ?? '');
    $closesAt = $record->ends_at ?? $record->closed_at ?? null;
    $closesAt = $closesAt ? \Carbon\Carbon::parse($closesAt) : null;
    // Stato reale del mercato (prima il badge diceva sempre "Active").
    $status = $record->resolved_at ? 'resolved' : (($closesAt !== null && $closesAt->isPast()) ? 'closed' : 'open');
    $statusClasses = [
        'open' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'closed' => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
        'resolved' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
    ][$status];
    $dotClasses = ['open' => 'bg-green-500', 'closed' => 'bg-gray-500', 'resolved' => 'bg-indigo-500'][$status];
@endphp
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-5">
        <a href="{{ url($locale.'/predicts') }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:underline dark:text-blue-400">
            <span aria-hidden="true">&larr;</span> {{ __('predict::market.detail.back') }}
        </a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>

        @if($description !== '')
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit(strip_tags($description), 300) }}</p>
        @endif

        <div class="mt-4 flex flex-wrap gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses }}">
                <span class="w-2 h-2 mr-2 rounded-full {{ $dotClasses }}" aria-hidden="true"></span>
                {{ __('predict::market.state.'.$status) }}
            </span>
            @if($record->volume_24h)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    {{ __('predict::market.detail.volume_24h', ['value' => number_format((float) $record->volume_24h, 0, ',', '.')]) }}
                </span>
            @endif
        </div>

        @if($closesAt)
            <p class="mt-3 flex items-center text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                {{ __($closesAt->isPast() ? 'predict::market.detail.closed_on' : 'predict::market.detail.closes', ['when' => $closesAt->diffForHumans(), 'date' => $closesAt->translatedFormat('j F Y')]) }}
            </p>
        @endif
    </div>
</div>
@else
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden p-6">
    <p class="text-gray-500 dark:text-gray-400 text-center">{{ __('predict::place_bet.widget.not_found') }}</p>
</div>
@endif
