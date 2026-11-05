<?php

declare(strict_types=1);

// NativePHP's embedded PHP may not expose the hostname extension. Keep
// framework and vendor packages portable across web and device runtimes.
if (! function_exists('gethostname')) {
    function gethostname(): string
    {
        return 'nativephp';
    }
}

// use Illuminate\Foundation\Application;
use App\Application;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // G-09 (DCP-282). Proxy fidati: config/trustedproxy.php (TRUSTED_PROXIES, default 127.0.0.1 = proxy dev HTTPS scripts/dev-https-proxy.mjs), che il
        // framework legge a ogni richiesta; `env()` qui non vedrebbe `.env`, ancora non caricato. Host fidati: config('security.trusted_hosts') (TRUSTED_HOSTS),
        // vuoto = nessuna restrizione; il framework salta il controllo con APP_ENV=local e nei test. I nomi diventano pattern esatti ancorati.
        $middleware->trustHosts(
            at: static fn (): array => array_values(array_map(
                static fn (string $host): string => '^'.preg_quote($host).'$',
                array_filter(config()->array('security.trusted_hosts', []), is_string(...)),
            )),
            subdomains: false,
        );
        // DCP-139: intestazioni di sicurezza su ogni risposta (valori in config/security.php, sezione headers)
        $middleware->append(SecurityHeaders::class);
        $middleware->redirectGuestsTo(static function (Request $request): string {
            $requestedLocale = $request->segment(1);
            $supportedLocales = array_keys((array) config('laravellocalization.supportedLocales', []));
            $locale = is_string($requestedLocale) && in_array($requestedLocale, $supportedLocales, true)
                ? $requestedLocale
                : LaravelLocalization::getCurrentLocale();
            app()->setLocale($locale);
            LaravelLocalization::setLocale($locale);
            $localizedLogin = LaravelLocalization::getLocalizedURL($locale, '/auth/login');

            return is_string($localizedLogin)
                ? $localizedLogin
                : '/'.$locale.'/auth/login';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 404 del front office con il layout del tema (DCP-151). Solo richieste HTML di un cittadino: API, JSON, Livewire, file e back office
        // restano alla risposta standard. Se il rendering fallisce si ricade sulla pagina di Laravel: un errore del layout non diventa un 500.
        $exceptions->render(static function (NotFoundHttpException $exception, Request $request) {
            if ($request->expectsJson() || $request->is('api/*', 'livewire/*', 'themes/*', 'storage/*', 'data/*', '*/assets/*', 'fixcity/*', '*/admin/*')) {
                return;
            }

            try {
                $first = $request->segment(1);
                if (is_string($first) && in_array($first, ['it', 'en', 'de', 'es'], true)) {
                    app()->setLocale($first);
                }

                return response()->view('fixcity::errors.not-found', [], 404);
            } catch (Throwable) {
                return;
            }
        });
        $exceptions->render(static function (TooManyRequestsHttpException $exception, Request $request) {
            // il tracciamento e la conferma con `?code=` sono serviti dalle pagine del tema (nome di rotta `container0.view`): `routeIs('tickets.track')`
            // vale solo per la pagina Folio del modulo, oscurata, e il cittadino vedeva la pagina nuda di Laravel (DCP-149)
            if (! $request->routeIs('tickets.track') && ! $request->is('*/tickets/track', '*/tickets/confirmation')) {
                return;
            }

            return response()->view(
                'fixcity::errors.too-many-requests',
                [],
                429,
                $exception->getHeaders(),
            );
        });
    })->create();
