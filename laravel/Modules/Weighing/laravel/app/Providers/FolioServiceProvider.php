<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Middleware\BlockLegacyPublicPages;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Laravel\Folio\Folio;
use Modules\Cms\Http\Middleware\SetFolioLocale;
use Modules\Xot\Datas\XotData;

class FolioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $themePagesPath = XotData::make()->getPubThemeViewPath('pages');
        $locales = array_keys((array) config('laravellocalization.supportedLocales', ['it' => [], 'en' => []]));
        if ($locales === []) {
            $locales = ['it', 'en'];
        }

        foreach ($locales as $locale) {
            Folio::path($themePagesPath)
                ->uri($locale)
                ->middleware([
                    '*' => [
                        SetFolioLocale::class,
                        BlockLegacyPublicPages::class,
                    ],
                ]);
        }

        $applicationPagesPath = resource_path('views/pages');
        if (File::isDirectory($applicationPagesPath)) {
            Folio::path($applicationPagesPath)->middleware([
                '*' => [
                    SetFolioLocale::class,
                ],
            ]);
        }
    }
}
