<?php

declare(strict_types=1);

return [
    'enabled' => (getenv('MOBILE_ENABLED') ?: true),
    'auth_guard' => (getenv('MOBILE_AUTH_GUARD') ?: 'api'),
    'offline_ttl' => (int) (getenv('MOBILE_OFFLINE_TTL') ?: 3600),
    'sync_interval' => (int) (getenv('MOBILE_SYNC_INTERVAL') ?: 30),
    'onesignal_app_id' => (getenv('NATIVEPHP_ONESIGNAL_APP_ID') ?: null),
    'device_storage' => storage_path('app/mobile'),
];
