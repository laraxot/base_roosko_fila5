<?php

declare(strict_types=1);

/**
 * Notify module environment credentials (env allowed here per Larastan).
 * Merged into notify.* module config by NotifyServiceProvider — STORY-288 pattern.
 */
return [
    'company' => [
        'name' => env('COMPANY_NAME', 'Default Company'),
        'team' => env('COMPANY_TEAM', 'Default Team'),
        'webhook_base' => env('WEBHOOK_BASE_URL', 'https://api.example.com'),
        'clinic_name' => env('CLINIC_NAME', 'Default Clinic'),
        'repository_url' => env('REPOSITORY_URL', 'https://github.com/example/repo'),
    ],
    'email' => [
        'default_from_address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
        'default_from_name' => env('MAIL_FROM_NAME', '{{company_name}}'),
        'default_admin_email' => env('ADMIN_EMAIL', 'admin@{{company_name}}.com'),
        'default_developer_email' => env('DEVELOPER_EMAIL', 'developer@{{company_name}}.com'),
    ],
    'paths' => [
        'default_avatar_path' => env('DEFAULT_AVATAR_PATH', '/images/avatars/default.svg'),
        'default_image_path' => env('DEFAULT_IMAGE_PATH', '/images/default.jpg'),
    ],
    'mail_layout' => [
        'logo_url' => env('MAIL_LOGO_URL'),
        'social_links' => [
            'facebook' => env('MAIL_SOCIAL_FACEBOOK'),
            'twitter' => env('MAIL_SOCIAL_TWITTER'),
            'instagram' => env('MAIL_SOCIAL_INSTAGRAM'),
            'linkedin' => env('MAIL_SOCIAL_LINKEDIN'),
        ],
        'unsubscribe_url' => env('MAIL_UNSUBSCRIBE_URL'),
    ],
    'sms' => [
        'default' => env('SMS_DRIVER', 'smsfactor'),
        'drivers' => [
            'smsfactor' => [
                'token' => env('SMSFACTOR_TOKEN'),
                'base_url' => env('SMSFACTOR_BASE_URL', 'https://api.smsfactor.com'),
            ],
            'twilio' => [
                'account_sid' => env('TWILIO_ACCOUNT_SID'),
                'auth_token' => env('TWILIO_AUTH_TOKEN'),
            ],
            'nexmo' => [
                'key' => env('NEXMO_KEY'),
                'secret' => env('NEXMO_SECRET'),
            ],
            'plivo' => [
                'auth_id' => env('PLIVO_AUTH_ID'),
                'auth_token' => env('PLIVO_AUTH_TOKEN'),
            ],
            'gammu' => [
                'path' => env('GAMMU_PATH', '/usr/bin/gammu'),
                'config' => env('GAMMU_CONFIG', '/etc/gammurc'),
            ],
            'netfun' => [
                'token' => env('NETFUN_TOKEN'),
                'api_url' => env('NETFUN_API_URL', 'https://v2.smsviainternet.it/api/rest/v1/sms-batch.json'),
                'circuit_breaker' => [
                    'threshold' => env('NETFUN_CIRCUIT_BREAKER_THRESHOLD', 5),
                    'timeout' => env('NETFUN_CIRCUIT_BREAKER_TIMEOUT', 60),
                ],
            ],
            'agiletelecom' => [
                'username' => env('AGILETELECOM_USERNAME'),
                'password' => env('AGILETELECOM_PASSWORD'),
                'sender' => env('AGILETELECOM_SENDER', 'MyApp'),
                'endpoint' => env('AGILETELECOM_API_URL', 'https://secure.agiletelecom.com/services/sms/send'),
            ],
        ],
        'debug' => env('SMS_DEBUG', false),
        'queue' => env('SMS_QUEUE', 'default'),
        'retry' => [
            'attempts' => env('SMS_RETRY_ATTEMPTS', 3),
            'delay' => env('SMS_RETRY_DELAY', 60),
        ],
        'rate_limit' => [
            'enabled' => env('SMS_RATE_LIMIT_ENABLED', true),
            'max_attempts' => env('SMS_RATE_LIMIT_MAX_ATTEMPTS', 60),
            'decay_minutes' => env('SMS_RATE_LIMIT_DECAY_MINUTES', 1),
        ],
        'circuit_breaker' => [
            'enabled' => env('SMS_CIRCUIT_BREAKER_ENABLED', true),
            'threshold' => env('SMS_CIRCUIT_BREAKER_THRESHOLD', 5),
            'timeout' => env('SMS_CIRCUIT_BREAKER_TIMEOUT', 60),
        ],
        'timeout' => env('SMS_TIMEOUT', 30),
        'logging' => [
            'enabled' => env('SMS_LOGGING_ENABLED', true),
            'channel' => env('SMS_LOGGING_CHANNEL', 'stack'),
        ],
        'validation' => [
            'enabled' => env('SMS_VALIDATION_ENABLED', true),
            'pattern' => env('SMS_VALIDATION_PATTERN', '/^\+[1-9]\d{1,14}$/'),
        ],
    ],
    'whatsapp' => [
        'default' => env('WHATSAPP_DRIVER', 'twilio'),
        'drivers' => [
            'twilio' => [
                'account_sid' => env('TWILIO_ACCOUNT_SID'),
                'auth_token' => env('TWILIO_AUTH_TOKEN'),
                'from' => env('TWILIO_WHATSAPP_FROM'),
            ],
            'vonage' => [
                'api_key' => env('VONAGE_KEY'),
                'api_secret' => env('VONAGE_SECRET'),
                'from' => env('VONAGE_WHATSAPP_FROM'),
            ],
            'facebook' => [
                'app_id' => env('FACEBOOK_APP_ID'),
                'app_secret' => env('FACEBOOK_APP_SECRET'),
                'access_token' => env('FACEBOOK_ACCESS_TOKEN'),
                'phone_number_id' => env('FACEBOOK_PHONE_NUMBER_ID'),
            ],
            '360dialog' => [
                'api_key' => env('360DIALOG_API_KEY'),
                'phone_number_id' => env('360DIALOG_PHONE_NUMBER_ID'),
            ],
        ],
        'debug' => env('WHATSAPP_DEBUG', false),
        'queue' => env('WHATSAPP_QUEUE', 'default'),
        'timeout' => env('WHATSAPP_TIMEOUT', 30),
        'from' => env('WHATSAPP_FROM'),
        'retry' => [
            'attempts' => env('WHATSAPP_RETRY_ATTEMPTS', 3),
            'delay' => env('WHATSAPP_RETRY_DELAY', 60),
        ],
        'rate_limit' => [
            'enabled' => env('WHATSAPP_RATE_LIMIT_ENABLED', true),
            'max_attempts' => env('WHATSAPP_RATE_LIMIT_MAX_ATTEMPTS', 60),
            'decay_minutes' => env('WHATSAPP_RATE_LIMIT_DECAY_MINUTES', 1),
        ],
    ],
    'telegram' => [
        'default' => env('TELEGRAM_DRIVER', 'official'),
        'drivers' => [
            'official' => [
                'token' => env('TELEGRAM_BOT_TOKEN'),
                'api_url' => env('TELEGRAM_API_URL', 'https://api.telegram.org'),
            ],
            'botman' => [
                'token' => env('TELEGRAM_BOT_TOKEN'),
                'api_url' => env('TELEGRAM_API_URL', 'https://api.telegram.org'),
                'webhook_url' => env('TELEGRAM_WEBHOOK_URL'),
            ],
            'nutgram' => [
                'token' => env('TELEGRAM_BOT_TOKEN'),
                'api_url' => env('TELEGRAM_API_URL', 'https://api.telegram.org'),
                'webhook_url' => env('TELEGRAM_WEBHOOK_URL'),
                'polling' => env('TELEGRAM_POLLING', false),
            ],
        ],
        'debug' => env('TELEGRAM_DEBUG', false),
        'queue' => env('TELEGRAM_QUEUE', 'default'),
        'timeout' => env('TELEGRAM_TIMEOUT', 30),
        'parse_mode' => env('TELEGRAM_PARSE_MODE', 'HTML'),
        'retry' => [
            'attempts' => env('TELEGRAM_RETRY_ATTEMPTS', 3),
            'delay' => env('TELEGRAM_RETRY_DELAY', 60),
        ],
        'rate_limit' => [
            'enabled' => env('TELEGRAM_RATE_LIMIT_ENABLED', true),
            'max_attempts' => env('TELEGRAM_RATE_LIMIT_MAX_ATTEMPTS', 30),
            'decay_minutes' => env('TELEGRAM_RATE_LIMIT_DECAY_MINUTES', 1),
        ],
    ],
];
