<?php

declare(strict_types=1);

namespace Modules\Geo\Providers\Filament;

use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Vite;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Pannello Filament del modulo Geo.
 *
 * Registra gli asset del coordinate picker (Lit + Leaflet) per tutti i pannelli
 * admin. Gli asset sono una build Vite in `public_html/assets/geo`, cartella
 * esclusa da git: se la build manca, `Vite::asset()` lancia
 * `ViteManifestNotFoundException` e manda in errore 500 TUTTI i pannelli, non
 * solo il campo mappa. Per questo la registrazione avviene solo se la build
 * (o il dev server Vite) è disponibile; altrimenti si registra un warning e i
 * pannelli restano utilizzabili.
 *
 * Build: `cd laravel/Modules/Geo && ../../node_modules/.bin/vite build`
 * (eseguita da `deploy.sh`).
 */
class AdminPanelProvider extends XotBasePanelProvider
{
    /**
     * Cartella della build Vite, relativa a `public_path()`.
     */
    public const string BUILD_DIRECTORY = 'assets/geo';

    protected string $module = 'Geo';

    #[\Override]
    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel);

        if (! inAdmin()) {
            return $panel;
        }

        if (! static::assetsAreAvailable()) {
            Log::warning('Geo: build Vite assente, coordinate picker non registrato.', [
                'manifest' => public_path(self::BUILD_DIRECTORY.'/manifest.json'),
            ]);

            return $panel;
        }

        FilamentAsset::register([
            Js::make('coordinate-picker', Vite::asset('resources/js/components/coordinate-picker-lit.js', self::BUILD_DIRECTORY))->module(),
            // app.css is intentionally an empty Vite entry; the map styles
            // are emitted next to the Lit entry and must be registered as CSS.
            Css::make('coordinate-picker', asset(self::BUILD_DIRECTORY.'/assets/coordinate-picker-lit.css')),
        ]);

        return $panel;
    }

    /**
     * Vero se gli asset del coordinate picker possono essere risolti:
     * dev server Vite attivo (file `hot`) oppure manifest della build presente.
     *
     * `$publicRoot` serve ai test: `App\Application::publicPath()` ignora
     * `usePublicPath()`, quindi un test non può spostare `public_path()`.
     */
    public static function assetsAreAvailable(?string $publicRoot = null): bool
    {
        $root = rtrim($publicRoot ?? public_path(), DIRECTORY_SEPARATOR);

        return File::exists($root.DIRECTORY_SEPARATOR.'hot')
            || File::exists($root.DIRECTORY_SEPARATOR.self::BUILD_DIRECTORY.'/manifest.json');
    }
}
