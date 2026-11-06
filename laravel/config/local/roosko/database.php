<?php

declare(strict_types=1);

use Illuminate\Support\Arr;

$applicationDatabase = env('DB_DATABASE', 'roosko_data');
$userDatabase = env('DB_DATABASE_USER', 'roosko_user');

// The User module owns users, devices, authentication logs and Spatie permissions/roles. Never point
// its connection at the application database, even if production has a stale
// DB_DATABASE_USER value copied from DB_DATABASE.
if ($userDatabase === $applicationDatabase) {
    $userDatabase = 'roosko_user';
}

// SQLite: ':memory:' e percorsi assoluti si usano cosi' come sono, un nome diventa database/{nome}.sqlite.
$sqlitePath = static fn (string $database): string => $database === ':memory:' || str_starts_with($database, '/')
    ? $database
    : database_path(preg_replace('/\.sqlite$/', '', $database).'.sqlite');

$res = [
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'roosko_data'),
            'username' => env('DB_USERNAME', 'roosko'),
            'password' => env('DB_PASSWORD', ''),
            // 'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            // 'collation' => env('DB_COLLATION', 'utf8mb4_uca1400_ai_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => $sqlitePath((string) $applicationDatabase),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'user_sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => database_path($userDatabase.'.sqlite'),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'user_mysql' => [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            // 'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
            'database' => $userDatabase,
            'username' => env('DB_USERNAME_USER', 'forge_user_04_1b'),
            'password' => env('DB_PASSWORD_USER', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'user_mariadb' => [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            // 'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
            'database' => $userDatabase,
            'username' => env('DB_USERNAME_USER', 'forge_user_02_1'),
            'password' => env('DB_PASSWORD_USER', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        // 'orbit' => [
        //     'driver' => 'sqlite',
        //     // "database" => Orbit::getDatabasePath(),
        //     // "database" => storage_path('framework/cache/orbit/orbit.sqlite'),
        //     // "database" => config('orbit.paths.cache') . DIRECTORY_SEPARATOR . 'orbit.sqlite',
        //     // "database" => '/var/www/html/_bases/base_siram_idoteca_fila5/laravel/config/local/idoteca/orbit.sqlite',
        //     'database' => app(\Modules\Tenant\Actions\Config\GetTenantFilePathAction::class)->execute('orbit.sqlite'),
        //     'foreign_key_constraints' => false,
        // ],
        // 'orbit_meta' => [
        //     'driver' => 'sqlite',
        //     // "database" => Orbit::getMetaDatabasePath(),
        //     'database' => storage_path('framework/cache/orbit/orbit_meta.sqlite'),
        //     'foreign_key_constraints' => false,
        // ],
    ],
];

$database_default = config('database.default');
Arr::set($res, 'connections.user', Arr::get($res, 'connections.user_'.$database_default));

return $res;
