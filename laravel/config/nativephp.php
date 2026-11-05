<?php

declare(strict_types=1);

return [
    'version' => env('NATIVEPHP_APP_VERSION', '1.0.0'),
    'version_code' => env('NATIVEPHP_APP_VERSION_CODE', 1),
    'app_id' => env('NATIVEPHP_APP_ID', 'com.fixcity.app'),
    'start_url' => env('NATIVEPHP_START_URL', '/mobile'),
    'deeplink_scheme' => env('NATIVEPHP_DEEPLINK_SCHEME', 'fixcity'),
    'deeplink_host' => env('NATIVEPHP_DEEPLINK_HOST', 'mobile'),

    'runtime' => [
        'mode' => env('NATIVEPHP_RUNTIME_MODE', 'persistent'),
        'reset_instances' => true,
    ],

    // The repository contains developer-only symlinks and tool workspaces.
    // Excluding them keeps NativePHP's rsync bundle deterministic and prevents
    // broken/cyclic links from entering the device archive.
    'cleanup_exclude_files' => [
        'fixtures',
        'app/Testing',
        'node_modules',
        'Modules/*/node_modules',
        'Modules/*/.agents',
        'Modules/*/.claude',
        'Modules/*/.codex',
        'Modules/*/.cursor',
        'Modules/*/.opencode',
        'Modules/*/.windsurf',
        'Themes/Sixteen/node_modules',
        'Themes/Sixteen/resources/views/bootstrap-italia/bootstrap-italia',
    ],

    'android' => [
        'compile_sdk' => env('NATIVEPHP_ANDROID_COMPILE_SDK', 36),
        'min_sdk' => env('NATIVEPHP_ANDROID_MIN_SDK', 26),
        'target_sdk' => env('NATIVEPHP_ANDROID_TARGET_SDK', 36),
        'build' => [
            'minify_enabled' => env('NATIVEPHP_ANDROID_MINIFY_ENABLED', false),
            'shrink_resources' => env('NATIVEPHP_ANDROID_SHRINK_RESOURCES', false),
            'obfuscate' => env('NATIVEPHP_ANDROID_OBFUSCATE', false),
        ],
    ],

    'permissions' => [
        'camera' => 'FixCity needs camera access to take photos of issues',
        'location' => 'FixCity needs location access to tag issues',
        'notifications' => 'FixCity needs to send you notifications about your reports',
    ],

    'orientation' => [
        'iphone' => [
            'portrait' => true,
            'landscape_left' => false,
            'landscape_right' => false,
        ],
        'android' => [
            'portrait' => true,
            'landscape_left' => false,
            'landscape_right' => false,
        ],
    ],
];
