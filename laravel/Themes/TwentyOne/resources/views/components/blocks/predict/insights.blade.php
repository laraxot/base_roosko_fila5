{{--
    Blocco CMS sotto "Scommetti" nella pagina del mercato: andamento delle probabilità, ultime
    scommesse e mercati collegati (le tre schede che Polymarket, Kalshi e Manifold mettono
    in ogni pagina di mercato). predictId arriva dai dati della pagina, come per place-bet.
--}}
@php
    $predictId = $data['predictId'] ?? $predictId ?? null;
    $predict = is_numeric($predictId) ? \Modules\Predict\Models\Predict::query()->with('ratings.media')->find((int) $predictId) : null;
    $locale = app()->getLocale();
@endphp
@if ($predict)
    @php
        $activity = app(\Modules\Predict\Actions\Predict\GetMarketActivityAction::class)->execute($predict);
        $related = app(\Modules\Predict\Actions\Predict\GetRelatedMarketsAction::class)->execute($predict);
    @endphp
    {{-- Regole di risoluzione (come Kalshi): da dove viene il risultato e quando si annulla. --}}
    <section class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800" aria-labelledby="rules-{{ $predict->id }}">
        <h2 id="rules-{{ $predict->id }}" class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">{{ __('predict::rules.title') }}</h2>
        <dl class="grid gap-x-8 gap-y-3 text-sm sm:grid-cols-2">
            @if ($predict->closed_at)
                <div>
                    <dt class="font-medium text-gray-900 dark:text-white">{{ __('predict::rules.closes') }}</dt>
                    <dd class="text-gray-700 dark:text-gray-300"><time datetime="{{ $predict->closed_at->toIso8601String() }}">{{ $predict->closed_at->translatedFormat('j F Y, H:i') }}</time></dd>
                </div>
            @endif
            @if (filled($predict->resolution_source))
                <div>
                    <dt class="font-medium text-gray-900 dark:text-white">{{ __('predict::rules.source') }}</dt>
                    <dd class="text-gray-700 dark:text-gray-300">{{ $predict->resolution_source }}</dd>
                </div>
            @endif
            @if (filled($predict->fallback_source))
                <div>
                    <dt class="font-medium text-gray-900 dark:text-white">{{ __('predict::rules.fallback') }}</dt>
                    <dd class="text-gray-700 dark:text-gray-300">{{ $predict->fallback_source }}</dd>
                </div>
            @endif
            <div>
                <dt class="font-medium text-gray-900 dark:text-white">{{ __('predict::rules.cancellation') }}</dt>
                <dd class="text-gray-700 dark:text-gray-300">{{ filled($predict->cancellation_rule) ? $predict->cancellation_rule : __('predict::rules.default_cancellation') }}</dd>
            </div>
        </dl>
        <p class="mt-4 border-t border-gray-100 pt-3 text-sm text-gray-600 dark:border-gray-700 dark:text-gray-400">{{ __('predict::rules.how') }}</p>
    </section>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-predict::probability-chart :predict="$predict" />
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800" aria-labelledby="activity-{{ $predict->id }}">
            <h2 id="activity-{{ $predict->id }}" class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('predict::insights.activity.title') }}</h2>
            @forelse ($activity as $item)
                <div class="flex items-center gap-3 border-b border-gray-100 py-2.5 last:border-0 dark:border-gray-700">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-800 dark:bg-blue-900/50 dark:text-blue-200" aria-hidden="true">{{ $item['initials'] }}</span>
                    <p class="min-w-0 flex-1 text-sm text-gray-700 dark:text-gray-300">
                        <span class="font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</span>
                        {{ __('predict::insights.activity.bet', ['credits' => number_format($item['credits'], 0, ',', '.')]) }}
                        <span class="font-medium text-gray-900 dark:text-white">{{ $item['outcome'] }}</span>
                    </p>
                    @if ($item['at'])
                        <time datetime="{{ $item['at']->toIso8601String() }}" class="shrink-0 text-xs text-gray-500 dark:text-gray-400">{{ $item['at']->diffForHumans(short: true) }}</time>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('predict::insights.activity.empty') }}</p>
            @endforelse
        </section>
    </div>

    @livewire('predict.market-discussion', ['predictId' => (int) $predict->id], key('discussion-'.$predict->id))

    @if ($related->isNotEmpty())
        <section class="mt-6" aria-labelledby="related-{{ $predict->id }}">
            <h2 id="related-{{ $predict->id }}" class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('predict::insights.related.title') }}</h2>
            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($related as $market)
                    @php($top = array_slice(app(\Modules\Predict\Actions\Predict\GetMarketOutcomesAction::class)->execute($market), 0, 2))
                    <a href="{{ url('/'.$locale.'/predicts/'.$market->slug) }}" class="group flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-colors hover:border-blue-600 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-blue-400">
                        <h3 class="font-semibold leading-snug text-gray-900 text-balance group-hover:text-blue-700 dark:text-white dark:group-hover:text-blue-300">{{ $market->title }}</h3>
                        <div class="flex flex-col gap-2">
                            @foreach ($top as $outcome)
                                <x-predict::outcome-row :title="$outcome['title']" :percentage="$outcome['percentage']" :image="$outcome['image']" :color="$outcome['color']" />
                            @endforeach
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endif
