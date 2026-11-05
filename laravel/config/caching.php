<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Caching Configuration
    |--------------------------------------------------------------------------
    |
    | Define caching strategies for the application to optimize performance.
    |
    */

    // Cache configuration
    'cache' => [
        'driver' => env('CACHE_DRIVER', env('APP_ENV') === 'production' ? 'redis' : 'file'),
        'ttl' => [
            'short' => 300,        // 5 minutes
            'medium' => 3600,      // 1 hour
            'long' => 86400,       // 1 day
            'forever' => 31536000, // 1 year
        ],
    ],

    // Query caching
    'queries' => [
        'cache_enabled' => env('QUERY_CACHE_ENABLED', env('APP_ENV') === 'production'),
        'default_ttl' => 3600,
        'patterns' => [
            'config' => 86400,      // Configuration queries cached for 1 day
            'settings' => 86400,
            'translations' => 86400,
            'permissions' => 3600,
            'user_roles' => 3600,
        ],
    ],

    // View/Fragment caching
    'views' => [
        'cache_enabled' => env('VIEW_CACHE_ENABLED', env('APP_ENV') === 'production'),
        'default_ttl' => 3600,
        'cache_fragments' => true,
    ],

    // API response caching
    'api' => [
        'cache_enabled' => env('API_CACHE_ENABLED', true),
        'cache_get_requests' => true,
        'default_ttl' => 300,
        'vary_by_user' => true,
        'vary_by_locale' => true,
        'exclude_patterns' => [
            '/auth/*',
            '/user/profile',
            '/data/*',
        ],
    ],

    // Session/State caching
    'session' => [
        'store' => env('CACHE_STORE', 'redis'),
        'prefix' => 'session_',
    ],

    // Cache invalidation strategies
    'invalidation' => [
        'on_create' => true,
        'on_update' => true,
        'on_delete' => true,
        'related_models' => true,
    ],

    // Cache warming
    'warmup' => [
        'enabled' => env('CACHE_WARMUP_ENABLED', env('APP_ENV') === 'production'),
        'on_deploy' => true,
        'schedule' => 'daily',
    ],
];
