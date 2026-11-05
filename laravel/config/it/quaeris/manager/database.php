<?php

declare(strict_types=1);
return [
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge11'),
            'username' => env('DB_USERNAME', 'forge12'),
            'password' => env('DB_PASSWORD', ''),
            // 'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'liveuser_general' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            // 'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
            'database' => env('DB_DATABASE_USER', 'forge13'),
            'username' => env('DB_USERNAME', 'forge14'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => 'liveuser_',
            'strict' => false,
            'engine' => null,
        ],

        '<nome progetto>' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            // 'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
            'database' => env('DB_DATABASE_<nome progetto>', 'forge26'),
            'username' => env('DB_USERNAME_<nome progetto>', 'forge27'),
            'password' => env('DB_PASSWORD_<nome progetto>', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'limesurvey' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            // 'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
            'database' => env('DB_DATABASE_LIMESURVEY', 'forge15'),
            'username' => env('DB_USERNAME_LIMESURVEY', 'forge16'),
            'password' => env('DB_PASSWORD_LIMESURVEY', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],
        'orbit' => [
            'driver' => 'sqlite',
            // "database" => Orbit::getDatabasePath(),
            // "database" => storage_path('framework/cache/orbit/orbit.sqlite'),
            // "database" => config('orbit.paths.cache') . DIRECTORY_SEPARATOR . 'orbit.sqlite',
            // "database" => '/var/www/html/_bases/base_siram_idoteca_fila3/laravel/config/local/idoteca/orbit.sqlite',
            'database' => app(\Modules\Tenant\Actions\Config\GetTenantFilePathAction::class)->execute('orbit.sqlite'),
            'foreign_key_constraints' => false,
        ],
        'orbit_meta' => [
            'driver' => 'sqlite',
            // "database" => Orbit::getMetaDatabasePath(),
            'database' => storage_path('framework/cache/orbit/orbit_meta.sqlite'),
            'foreign_key_constraints' => false,
        ],
    ], // end connections
];
