{{--
    Scheda di un mercato nell'elenco (PredictTableWidget, tema TwentyOne).

    Gerarchia (passaggio impeccable/ui-ux-pro-max, 2026-11-10): stato e categoria → domanda →
    risposte (foto + barra di probabilità, le 4 più probabili, poi "+N altre risposte") (si leggono e si confrontano a colpo d'occhio, al posto dei
    quadrati con l'iniziale) → numeri e data di chiusura → un solo pulsante "Apri il mercato".
    Tolti: badge "Scommettibile" (ripeteva "Aperto"), autore e data di pubblicazione (rumore),
    data in formato ISO. Ogni area cliccabile è alta almeno 44px.
--}}
@php
    $record = $getRecord();
    $locale = app()->getLocale();
    $url = url('/'.$locale.'/predicts/'.$record->slug);
    $closedAt = $record->closed_at;
    $status = $record->resolved_at ? 'resolved' : ($closedAt?->isPast() ? 'closed' : $record->status);
    $canBet = auth()->check() && $status === 'open';
    // Risposte ordinate dalla più probabile; nella scheda le prime 4, le altre si vedono nel mercato.
    $outcomes = app(\Modules\Predict\Actions\Predict\GetMarketOutcomesAction::class)->execute($record);
    $visibleOutcomes = array_slice($outcomes, 0, 4);
    $hiddenCount = max(0, count($outcomes) - count($visibleOutcomes));
    $betsCount = (int) ($record->count_credit ?? 0);
    $creditsPlayed = (float) ($record->sum_credit ?? 0);
@endphp
<article class="flex h-full flex-col gap-4 p-1">
    <header class="flex flex-wrap items-center gap-x-3 gap-y-1">
        <x-predict.status-badge :status="$status" :with-animation="false" />
        @if ($record->category)
            <span class="text-xs text-gray-600 dark:text-gray-400">{{ $record->getBloodlineCategories() }}</span>
        @endif
    </header>

    <h3 class="text-base font-semibold leading-snug text-gray-900 text-balance dark:text-white">
        <a href="{{ $url }}" class="rounded-sm hover:text-blue-700 hover:underline hover:underline-offset-4 dark:hover:text-blue-300">
            {{ $record->title }}
        </a>
    </h3>

    @if ($visibleOutcomes !== [])
        <ul class="flex flex-col gap-2" aria-label="{{ __('predict::predict_card.options') }}">
            @foreach ($visibleOutcomes as $outcome)
                <li>
                    @if ($canBet)
                        <x-predict::outcome-row :title="$outcome['title']" :percentage="$outcome['percentage']" :image="$outcome['image']" :color="$outcome['color']" :winner="$outcome['winner']" :interactive="true"
                            wire:click="mountAction('bet', {{ \Illuminate\Support\Js::from(['predict_id' => $record->id, 'rating_id' => $outcome['id']]) }})"
                            aria-label="{{ __('predict::place_bet.widget.bet_on', ['option' => $outcome['title']]) }}{{ $outcome['percentage'] !== null ? ' — '.number_format($outcome['percentage'], 0, ',', '.').'%' : '' }}" />
                    @else
                        <x-predict::outcome-row :title="$outcome['title']" :percentage="$outcome['percentage']" :image="$outcome['image']" :color="$outcome['color']" :winner="$outcome['winner']" />
                    @endif
                </li>
            @endforeach
        </ul>
        @if ($hiddenCount > 0)
            <a href="{{ $url }}" class="-mt-2 text-sm font-medium text-blue-700 underline-offset-4 hover:underline dark:text-blue-300">
                {{ trans_choice('predict::predict_card.more_outcomes', $hiddenCount, ['count' => $hiddenCount]) }}
            </a>
        @endif
    @endif

    <footer class="mt-auto flex flex-col gap-3 border-t border-gray-200 pt-3 dark:border-gray-700">
        <dl class="flex flex-wrap justify-between gap-x-4 gap-y-1 text-xs text-gray-600 dark:text-gray-400">
            <div class="flex gap-1">
                <dt class="sr-only">{{ __('predict::predict_card.bets') }}</dt>
                <dd class="tabular-nums">{{ trans_choice('predict::predict_card.bets_count', $betsCount, ['count' => number_format($betsCount, 0, ',', '.')]) }}
                    · {{ __('predict::predict_card.credits_played', ['credits' => number_format($creditsPlayed, 0, ',', '.')]) }}</dd>
            </div>
            @if ($closedAt)
                <div class="flex gap-1">
                    <dt class="sr-only">{{ __('predict::predict_card.closing') }}</dt>
                    <dd>
                        <time datetime="{{ $closedAt->toDateString() }}">
                            {{ __($closedAt->isPast() ? 'predict::predict_card.closed_on' : 'predict::predict_card.closes_on', ['date' => $closedAt->translatedFormat('j M Y')]) }}
                        </time>
                    </dd>
                </div>
            @endif
        </dl>
        <a href="{{ $url }}"
           class="inline-flex min-h-11 items-center justify-center rounded-lg bg-blue-700 px-4 text-sm font-semibold text-white transition-colors duration-150 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-500">
            {{ __('predict::predict_card.open') }}
        </a>
    </footer>

    <x-filament-actions::modals />
</article>
