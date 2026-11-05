<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Intestazioni di sicurezza HTTP (DCP-139, GDPR art. 32). Registrato come middleware globale in bootstrap/app.php;
 * ogni valore sta in config/security.php (sezione `headers`). Un'intestazione gia' impostata dalla risposta non viene toccata.
 *
 * La CSP completa parte in `report-only` (le violazioni compaiono solo nella console del browser, nulla viene bloccato):
 * si passa a `enforce` con SECURITY_CSP_MODE=enforce dopo aver verificato la console. `frame-ancestors` e' invece sempre applicata.
 */
class SecurityHeaders
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Il server integrato di PHP (php artisan serve) aggiunge X-Powered-By da solo: si toglie a livello SAPI, non dal Response.
        if (! headers_sent()) {
            header_remove('X-Powered-By');
        }

        if (config('security.headers.enabled', true) !== true) {
            return $response;
        }

        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => $this->text('frame_options', 'SAMEORIGIN'),
            'Referrer-Policy' => $this->text('referrer_policy', 'strict-origin-when-cross-origin'),
            'Permissions-Policy' => $this->permissionsPolicy(),
            ...$this->contentSecurityPolicy(),
        ];
        // HSTS solo se la richiesta e' davvero HTTPS (dietro proxy conta solo se il proxy e' fidato: trustProxies in bootstrap/app.php).
        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = $this->hsts();
        }

        foreach ($headers as $name => $value) {
            if ($value !== '' && ! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }

    private function text(string $key, string $default): string
    {
        $value = config('security.headers.'.$key, $default);

        return is_string($value) ? $value : $default;
    }

    /** `geolocation=(self)`: il pulsante «Usa la tua posizione» del passo 2 usa navigator.geolocation. */
    private function permissionsPolicy(): string
    {
        $features = config('security.headers.permissions_policy', []);
        $parts = [];
        foreach (is_array($features) ? $features : [] as $feature => $allowlist) {
            if (is_string($feature) && is_string($allowlist)) {
                $parts[] = $feature.'='.$allowlist;
            }
        }

        return implode(', ', $parts);
    }

    private function hsts(): string
    {
        $maxAge = config('security.headers.hsts.max_age', 31536000);
        if (! is_int($maxAge) || $maxAge <= 0) {
            return '';
        }

        return 'max-age='.$maxAge
            .(config('security.headers.hsts.include_subdomains') === true ? '; includeSubDomains' : '')
            .(config('security.headers.hsts.preload') === true ? '; preload' : '');
    }

    /**
     * @return array<string, string>
     */
    private function contentSecurityPolicy(): array
    {
        $mode = config('security.headers.csp.mode', 'report-only');
        $always = $this->directives(config('security.headers.csp.enforced', []));
        $full = $this->directives(config('security.headers.csp.directives', [])) + $always;

        return match ($mode) {
            'enforce' => ['Content-Security-Policy' => $this->serialize($full)],
            'report-only' => [
                'Content-Security-Policy' => $this->serialize($always),
                'Content-Security-Policy-Report-Only' => $this->serialize($full),
            ],
            default => [],
        };
    }

    /**
     * @return array<string, list<string>>
     */
    private function directives(mixed $config): array
    {
        $directives = [];
        foreach (is_array($config) ? $config : [] as $name => $sources) {
            if (is_string($name) && is_array($sources)) {
                $directives[$name] = array_values(array_filter($sources, 'is_string'));
            }
        }

        return $directives;
    }

    /**
     * @param  array<string, list<string>>  $directives
     */
    private function serialize(array $directives): string
    {
        $parts = [];
        foreach ($directives as $name => $sources) {
            $parts[] = trim($name.' '.implode(' ', $sources));
        }

        return implode('; ', $parts);
    }
}
