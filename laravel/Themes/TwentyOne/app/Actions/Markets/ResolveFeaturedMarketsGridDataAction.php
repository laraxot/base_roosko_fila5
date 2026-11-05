<?php

declare(strict_types=1);

namespace Themes\TwentyOne\Actions\Markets;

use Illuminate\Support\Collection;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\QueueableAction\QueueableAction;

/**
 * Dati griglia mercati homepage TwentyOne (tema Predict).
 * Su basi senza Modules\Predict (FixCity) restituisce copy statico e cards vuote.
 */
final class ResolveFeaturedMarketsGridDataAction
{
    use QueueableAction;

    private const MARKET_CARDS_ACTION = 'Modules\\Predict\\Actions\\Homepage\\GetHomepageMarketCardsAction';

    /**
     * @return array{
     *     title:string,
     *     subtitle:string,
     *     showAllLink:string,
     *     showAllLabel:string,
     *     openMarketLabel:string,
     *     activeMarketsLabel:string,
     *     multiOutcomeLabel:string,
     *     visualOptionsLabel:string,
     *     freshnessLabel:string,
     *     educationLabel:string,
     *     emptyStateTitle:string,
     *     emptyStateBody:string,
     *     cards:Collection<int, array{
     *         title:string,
     *         slug:string,
     *         url:string,
     *         image_url:string|null,
     *         category:string|null,
     *         participants:int,
     *         volume:float,
     *         ends_at_human:string|null,
     *         outcomes:array<int, array{title:string, percentage:float, image_url:string|null}>
     *     }>
     * }
     */
    public function execute(?string $title = null, ?string $subtitle = null, ?string $showAllLink = null, int $limit = 12): array
    {
        $locale = app()->getLocale();
        $localized = LaravelLocalization::getLocalizedURL($locale, '/predicts');
        $defaultShowAll = is_string($localized) && $localized !== ''
            ? $localized
            : url('/'.$locale.'/predicts');

        return [
            'title' => $title ?? $this->tx('predict::home.featured_markets.title', 'Mercati in evidenza'),
            'subtitle' => $subtitle ?? $this->tx('predict::home.featured_markets.subtitle', 'Selezione multi-opzione dal database'),
            'showAllLink' => $showAllLink ?? $defaultShowAll,
            'showAllLabel' => $this->tx('predict::home.featured_markets.cta_all', 'Vedi tutti'),
            'openMarketLabel' => $this->tx('predict::actions.trade_market', 'Apri mercato'),
            'activeMarketsLabel' => $this->tx('predict::home.featured_markets.active_markets', 'Mercati attivi'),
            'multiOutcomeLabel' => $this->tx('predict::home.featured_markets.multi_outcome_focus', 'Multi-opzione'),
            'visualOptionsLabel' => $this->tx('predict::home.featured_markets.visual_options', 'Opzioni visuali'),
            'freshnessLabel' => $this->tx('predict::home.featured_markets.freshness', 'Aggiornati di recente'),
            'educationLabel' => $this->tx('predict::home.featured_markets.education', 'Impara mentre prevedi'),
            'emptyStateTitle' => $this->tx('predict::predict_table.empty_state.no_markets_available.message', 'Nessun mercato disponibile'),
            'emptyStateBody' => $this->tx('predict::home.featured_markets.empty_body', 'Torna piu tardi per nuove previsioni.'),
            'cards' => $this->resolveCards($limit),
        ];
    }

    /**
     * @return Collection<int, array{
     *     title:string,
     *     slug:string,
     *     url:string,
     *     image_url:string|null,
     *     category:string|null,
     *     participants:int,
     *     volume:float,
     *     ends_at_human:string|null,
     *     outcomes:array<int, array{title:string, percentage:float, image_url:string|null}>
     * }>
     */
    private function resolveCards(int $limit): Collection
    {
        if (! class_exists(self::MARKET_CARDS_ACTION)) {
            return collect();
        }

        $action = app(self::MARKET_CARDS_ACTION);
        if (! is_object($action) || ! method_exists($action, 'execute')) {
            return collect();
        }

        $cards = $action->execute($limit, 'featured');

        return $cards instanceof Collection ? $cards : collect();
    }

    private function tx(string $key, string $fallback): string
    {
        $translated = __($key);
        if (is_string($translated) && $translated !== $key) {
            return $translated;
        }

        $translatedLabel = __($key.'.label');
        if (is_string($translatedLabel) && $translatedLabel !== $key.'.label') {
            return $translatedLabel;
        }

        return $fallback;
    }
}
