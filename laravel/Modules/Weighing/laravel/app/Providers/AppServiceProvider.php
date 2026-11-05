<?php

declare(strict_types=1);

namespace App\Providers;

use Composer\Autoload\ClassLoader;
use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Foundation\Vite;
use Illuminate\Support\ServiceProvider;
use Modules\Xot\Actions\Composer\RegisterRuntimePsr4NamespacesAction;
use Nwidart\Modules\LaravelModulesServiceProvider;

/**
 * AppServiceProvider.
 *
 * NOTE: Theme registration is handled dynamically by the Xot module
 * based on configuration in config/{environment}/{domain}/xra.php
 *
 * The theme is a "vestito" (outfit) - configurable and swappable!
 *
 * Configuration example:
 * - config/local/roosko/xra.php: 'pub_theme' => 'TwentyOne'
 * - config/local/roosko/xra.php: 'register_pub_theme' => true
 *
 * The Xot module reads this config and automatically registers
 * the appropriate ThemeServiceProvider at runtime.
 *
 * DO NOT hardcode theme registration here!
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerRuntimeNamespaces();

        // NativePHP boots from a cache-less bundle and may resolve cache while
        // deferred service metadata is still being restored. Materialize the
        // framework cache provider early; the guard keeps web boot idempotent.
        if (! $this->app->bound('cache')) {
            $this->app->register(CacheServiceProvider::class);
        }

        if (! $this->app->bound('modules')) {
            $this->app->register(LaravelModulesServiceProvider::class);
        }
    }

    /**
     * Register application-owned theme namespaces before provider/view resolution.
     * NativePHP bundles do not retain Laravel's generated service manifests.
     */
    private function registerRuntimeNamespaces(): void
    {
        $autoloadPath = base_path('vendor/autoload.php');

        if (! is_file($autoloadPath)) {
            return;
        }

        $loader = require $autoloadPath;

        if (! $loader instanceof ClassLoader) {
            return;
        }

        (new RegisterRuntimePsr4NamespacesAction)->execute($loader);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('NATIVEPHP_RUNNING')) {
            app(Vite::class)->useManifestFilename('.vite/manifest.json');
        }

        // Theme registration is handled dynamically by Xot module
        // No hardcoded theme registration here!
    }
}
