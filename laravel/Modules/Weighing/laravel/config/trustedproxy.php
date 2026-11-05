<?php

declare(strict_types=1);

/*
 * Proxy fidati (G-09, DCP-282). Letta da `Illuminate\Http\Middleware\TrustProxies` a ogni richiesta, quando `.env` e' gia' caricato:
 * `env()` dentro `bootstrap/app.php` non lo vedrebbe, perche' il callback di `withMiddleware` gira prima del caricamento di `.env`.
 *
 * TRUSTED_PROXIES: IP o CIDR separati da virgola dei proxy che possono dichiarare schema e host con gli header X-Forwarded-*. Il default
 * `127.0.0.1` e' quello di sempre (proxy HTTPS di sviluppo, scripts/dev-https-proxy.mjs). Dietro un load balancer indicare il suo IP o la sua rete;
 * `*` si fida di chiunque e NON va usato in produzione senza un firewall davanti.
 */
return [
    'proxies' => env('TRUSTED_PROXIES') ?: '127.0.0.1',
];
