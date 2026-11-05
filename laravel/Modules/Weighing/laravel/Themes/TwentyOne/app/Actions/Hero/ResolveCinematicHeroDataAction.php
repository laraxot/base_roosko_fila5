<?php

declare(strict_types=1);

namespace Themes\TwentyOne\Actions\Hero;

use Illuminate\Support\Collection;
use Spatie\QueueableAction\QueueableAction;
use Throwable;

/**
 * Hero cinematografico homepage TwentyOne (tema Predict).
 * Su basi senza Modules\Predict (FixCity) usa solo copy/fallback tipizzati.
 */
final class ResolveCinematicHeroDataAction
{
    use QueueableAction;

    private const HERO_STATS_ACTION = 'Modules\\Predict\\Actions\\Homepage\\GetHomepageHeroDataAction';

    private const MARKET_CARDS_ACTION = 'Modules\\Predict\\Actions\\Homepage\\GetHomepageMarketCardsAction';

    /**
     * @param  array{text?:string, url?:string}|null  $ctaPrimary
     * @param  array{text?:string, url?:string}|null  $ctaSecondary
     * @return array{
     *     eyebrow:string,
     *     title:string,
     *     subtitle:string,
     *     primaryCta:array{text:string,url:string},
     *     secondaryCta:array{text:string,url:string},
     *     stats:array<int, array{value:string,label:string,icon:string}>,
     *     highlights:array<int, string>,
     *     spotlightCards:array<int, array{
     *         title:string,
     *         slug:string,
     *         url:string,
     *         image_url:string|null,
     *         category:string|null,
     *         participants:int,
     *         volume:float,
     *         ends_at_human:string|null,
     *         outcomes:array<int, array{title:string,percentage:float,image_url:string|null}>,
     *         lead_outcome:array{title:string,percentage:float,image_url:string|null}|null
     *     }>
     * }
     */
    public function execute(
        ?string $heroTitle = null,
        ?string $heroSubtitle = null,
        ?array $ctaPrimary = null,
        ?array $ctaSecondary = null,
    ): array {
        $locale = app()->getLocale();
        $heroStats = $this->resolveHeroStats();

        $data = [
            'eyebrow' => $this->translateText('predict::home.hero.eyebrow.label', 'Mercati multi-opzione in primo piano'),
            'title' => $heroTitle ?? $this->translateText('predict::home.hero.title', 'Prevedi il Futuro'),
            'subtitle' => $heroSubtitle ?? $this->translateText('predict::home.hero.subtitle', 'Mercati visuali, percentuali calcolate dal database e flussi pensati per capire chi sta davvero salendo.'),
            'primaryCta' => [
                'text' => (string) ($ctaPrimary['text'] ?? $this->translateText('predict::home.hero.cta_primary.label', 'Esplora i mercati')),
                'url' => (string) ($ctaPrimary['url'] ?? $this->localizedUrl($locale, '/predicts')),
            ],
            'secondaryCta' => [
                'text' => (string) ($ctaSecondary['text'] ?? $this->translateText('predict::home.hero.cta_secondary.label', 'Crea un account')),
                'url' => (string) ($ctaSecondary['url'] ?? $this->localizedUrl($locale, '/register')),
            ],
            'stats' => $this->buildStats($heroStats),
            'highlights' => [
                $this->translateText('predict::home.hero.highlights.visual_first', 'Foto reali delle opzioni, non solo etichette'),
                $this->translateText('predict::home.hero.highlights.multi_outcome', 'Solo mercati con almeno 4 possibili risposte'),
                $this->translateText('predict::home.hero.highlights.db_backed', 'Percentuali e volumi derivati dai dati salvati nel DB'),
            ],
            'spotlightCards' => [],
        ];

        try {
            $data['spotlightCards'] = $this->buildSpotlightCards();
        } catch (Throwable $e) {
            report($e);
        }

        return $data;
    }

    /**
     * @return array{marketsCount:int, usersCount:int, volumeCredits:int}
     */
    private function resolveHeroStats(): array
    {
        $defaults = ['marketsCount' => 0, 'usersCount' => 0, 'volumeCredits' => 0];
        if (! class_exists(self::HERO_STATS_ACTION)) {
            return $defaults;
        }

        $action = app(self::HERO_STATS_ACTION);
        if (! is_object($action) || ! method_exists($action, 'execute')) {
            return $defaults;
        }

        $stats = $action->execute();
        if (! is_array($stats)) {
            return $defaults;
        }

        $markets = $stats['marketsCount'] ?? 0;
        $users = $stats['usersCount'] ?? 0;
        $volume = $stats['volumeCredits'] ?? 0;

        return [
            'marketsCount' => is_numeric($markets) ? (int) $markets : 0,
            'usersCount' => is_numeric($users) ? (int) $users : 0,
            'volumeCredits' => is_numeric($volume) ? (int) $volume : 0,
        ];
    }

    private function translateText(string $key, string $fallback): string
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

    /**
     * @param  array{marketsCount:int,usersCount:int,volumeCredits:int}  $heroStats
     * @return array<int, array{value:string,label:string,icon:string}>
     */
    private function buildStats(array $heroStats): array
    {
        return [
            [
                'value' => $this->formatCompact((int) $heroStats['marketsCount']),
                'label' => $this->translateText('predict::home.hero.stats.active_markets', 'Mercati attivi'),
                'icon' => 'heroicon-o-presentation-chart-bar',
            ],
            [
                'value' => $this->formatCompact((int) $heroStats['usersCount']),
                'label' => $this->translateText('predict::home.hero.stats.users', 'Utenti registrati'),
                'icon' => 'heroicon-o-user-group',
            ],
            [
                'value' => $this->formatCompact((int) $heroStats['volumeCredits']),
                'label' => $this->translateText('predict::home.hero.stats.volume', 'Crediti scambiati'),
                'icon' => 'heroicon-o-banknotes',
            ],
        ];
    }

    /**
     * @return array<int, array{
     *     title:string,
     *     slug:string,
     *     url:string,
     *     image_url:string|null,
     *     category:string|null,
     *     participants:int,
     *     volume:float,
     *     ends_at_human:string|null,
     *     outcomes:array<int, array{title:string,percentage:float,image_url:string|null}>,
     *     lead_outcome:array{title:string,percentage:float,image_url:string|null}|null
     * }>
     */
    private function buildSpotlightCards(): array
    {
        if (! class_exists(self::MARKET_CARDS_ACTION)) {
            return [];
        }

        $action = app(self::MARKET_CARDS_ACTION);
        if (! is_object($action) || ! method_exists($action, 'execute')) {
            return [];
        }

        $cards = $action->execute(3, 'featured');
        if (! $cards instanceof Collection) {
            return [];
        }

        $result = [];
        foreach ($cards as $card) {
            if (! is_array($card)) {
                continue;
            }
            $title = $card['title'] ?? '';
            $slug = $card['slug'] ?? '';
            $url = $card['url'] ?? '';
            if (! is_string($title) || ! is_string($slug) || ! is_string($url)) {
                continue;
            }
            $rawOutcomes = $card['outcomes'] ?? [];
            $outcomes = [];
            if (is_array($rawOutcomes)) {
                foreach ($rawOutcomes as $outcome) {
                    if (! is_array($outcome)) {
                        continue;
                    }
                    $oTitle = $outcome['title'] ?? '';
                    $oPct = $outcome['percentage'] ?? 0;
                    $oImg = $outcome['image_url'] ?? null;
                    if (! is_string($oTitle)) {
                        continue;
                    }
                    $outcomes[] = [
                        'title' => $oTitle,
                        'percentage' => is_numeric($oPct) ? (float) $oPct : 0.0,
                        'image_url' => is_string($oImg) ? $oImg : null,
                    ];
                }
            }
            $image = $card['image_url'] ?? null;
            $category = $card['category'] ?? null;
            $participants = $card['participants'] ?? 0;
            $volume = $card['volume'] ?? 0;
            $ends = $card['ends_at_human'] ?? null;
            $result[] = [
                'title' => $title,
                'slug' => $slug,
                'url' => $url,
                'image_url' => is_string($image) ? $image : null,
                'category' => is_string($category) ? $category : null,
                'participants' => is_numeric($participants) ? (int) $participants : 0,
                'volume' => is_numeric($volume) ? (float) $volume : 0.0,
                'ends_at_human' => is_string($ends) ? $ends : null,
                'outcomes' => $outcomes,
                'lead_outcome' => $outcomes[0] ?? null,
            ];
        }

        return $result;
    }

    private function localizedUrl(string $locale, string $path): string
    {
        $localization = app('laravellocalization');
        $resolved = $localization->getLocalizedURL($locale, $path);

        return is_string($resolved) && $resolved !== ''
            ? $resolved
            : url('/'.$locale.$path);
    }

    private function formatCompact(int $value): string
    {
        if ($value >= 1000000) {
            return number_format($value / 1000000, 1, ',', '.').'M';
        }

        if ($value >= 1000) {
            return number_format($value / 1000, 1, ',', '.').'K';
        }

        return number_format($value, 0, ',', '.');
    }
}
