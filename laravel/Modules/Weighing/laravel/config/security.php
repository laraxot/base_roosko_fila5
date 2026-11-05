<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for the
    | application. Ensure these settings align with your security policy.
    |
    */

    // Session security
    'session' => [
        'lifetime' => (int) env('SESSION_LIFETIME', 60),
        'http_only' => true,
        'secure' => env('APP_ENV') === 'production',
        'same_site' => 'lax',
        'path' => '/',
        'domain' => env('SESSION_DOMAIN', null),
    ],

    // Rate limiting
    'rate_limit' => [
        'max_attempts' => (int) env('LOGIN_RATE_LIMIT', 5),
        'decay_minutes' => (int) env('LOGIN_RATE_LIMIT_DECAY', 15),
    ],

    // Password policy
    'password' => [
        'min_length' => 12,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'expiration_days' => 90,
    ],

    // File upload
    'file_upload' => [
        'max_size' => 5242880, // 5MB
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
        'storage_path' => 'uploads',
        'scan_for_malware' => true,
    ],

    // CORS security
    'cors' => [
        'allowed_origins' => array_filter(explode(',', (string) env('CORS_ALLOWED_ORIGINS', 'http://localhost'))),
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'Authorization'],
        'exposed_headers' => [],
        'max_age' => 3600,
    ],

    // Host fidati (G-09, DCP-282): nomi host esatti, senza porta, separati da virgola (TRUSTED_HOSTS). Vuoto = nessuna restrizione (comportamento di sempre).
    // Impostata, `bootstrap/app.php` rifiuta con 400 ogni richiesta con un altro `Host`: l'header non finisce piu' in sitemap, canonical e link assoluti.
    // Il framework applica il controllo solo fuori da APP_ENV=local e fuori dai test.
    'trusted_hosts' => array_values(array_filter(array_map(trim(...), explode(',', (string) env('TRUSTED_HOSTS', ''))))),

    // API Security
    'api' => [
        'version' => env('API_VERSION', 'v1'),
        'timeout' => 30,
        'max_request_size' => 10485760, // 10MB
    ],

    // Two-factor authentication
    'two_factor' => [
        'enabled' => env('TWO_FACTOR_ENABLED', false),
        'required_for_admins' => env('TWO_FACTOR_REQUIRED_ADMINS', true),
        'grace_period_days' => 7,
    ],

    // Encryption
    'encryption' => [
        'algorithm' => env('APP_CIPHER', 'AES-256-CBC'),
        'sensitive_fields' => [
            'password',
            'email',
            'phone',
            'ssn',
            'credit_card',
        ],
    ],

    // Logging & Monitoring
    'logging' => [
        'log_authentication' => true,
        'log_authorization' => true,
        'log_data_modifications' => true,
        'sanitize_logs' => true,
        'retention_days' => 90,
    ],

    // Security Headers (DCP-139): letti da App\Http\Middleware\SecurityHeaders (registrato in bootstrap/app.php)
    'headers' => [
        'enabled' => (bool) env('SECURITY_HEADERS_ENABLED', true),
        'frame_options' => 'SAMEORIGIN',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        // geolocation=(self): il pulsante «Usa la tua posizione» del passo 2 usa navigator.geolocation (con () sarebbe disattivato).
        'permissions_policy' => [
            'geolocation' => '(self)',
            // camera=(self): lo scanner QR della pagina operatore di Roosko usa getUserMedia (con () sarebbe bloccato).
            'camera' => '(self)',
            'microphone' => '()',
            'payment' => '()',
            'usb' => '()',
            'accelerometer' => '()',
            'gyroscope' => '()',
            'magnetometer' => '()',
        ],
        // Solo su richieste HTTPS. max_age 0 la disattiva. includeSubDomains e preload sono impegni a lungo termine: spenti finche' il dominio di produzione non e' deciso.
        'hsts' => [
            'max_age' => (int) env('SECURITY_HSTS_MAX_AGE', 31536000),
            'include_subdomains' => (bool) env('SECURITY_HSTS_INCLUDE_SUBDOMAINS', false),
            'preload' => (bool) env('SECURITY_HSTS_PRELOAD', false),
        ],
        'csp' => [
            // off | report-only (nulla viene bloccato, le violazioni compaiono nella console) | enforce
            'mode' => env('SECURITY_CSP_MODE', 'report-only'),
            // Sempre applicate, anche in report-only (non possono rompere una pagina che non sia incorniciata da un altro sito).
            'enforced' => [
                'frame-ancestors' => ["'self'"],
            ],
            // Lista MISURATA con bashscripts/tools/visual/net-hosts-probe.mjs (DCP-139, 2026-10-13): vedi la story per pagine e conteggi.
            // 'unsafe-inline' e 'unsafe-eval' negli script servono a Livewire/Alpine (espressioni x-data) e agli script inline di Blade:
            // senza di loro la CSP non protegge dall'XSS negli script (decisione aperta, vedi story).
            'directives' => [
                'default-src' => ["'self'"],
                'script-src' => ["'self'", "'unsafe-inline'", "'unsafe-eval'"],
                // Lit/Map widgets, heic2any and the player create workers from Blob URLs.
                // Keep worker-src explicit so enforce mode does not fall back to script-src.
                'worker-src' => ["'self'", 'blob:'],
                'style-src' => ["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com'],
                'font-src' => ["'self'", 'data:', 'https://fonts.gstatic.com'],
                'img-src' => ["'self'", 'data:', 'blob:', 'https://*.tile.openstreetmap.org'],
                'connect-src' => ["'self'", 'https://nominatim.openstreetmap.org'],
                'frame-src' => ["'self'"],
                'object-src' => ["'none'"],
                'base-uri' => ["'self'"],
                'form-action' => ["'self'"],
            ],
        ],
    ],

    // Database security
    'database' => [
        'encrypt_at_rest' => env('DB_ENCRYPT_AT_REST', false),
        'audit_trail' => true,
        'query_logging' => env('DB_QUERY_LOG', false),
    ],
];
