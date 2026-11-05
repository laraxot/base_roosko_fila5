<?php

declare(strict_types=1);

use Modules\Tenant\Actions\Config\GetTenantFilePathAction;
use Orbit\Drivers\Json;
use Orbit\Drivers\Markdown;
use Orbit\Drivers\Yaml;

return [
    'default' => env('ORBIT_DEFAULT_DRIVER', 'md'),

    'drivers' => [
        'md' => Markdown::class,
        'json' => Json::class,
        'yaml' => Yaml::class,
    ],

    'paths' => [
        'content' => app(GetTenantFilePathAction::class)->execute('database/content'),
        'cache' => app(GetTenantFilePathAction::class)->execute(''),
    ],
];
