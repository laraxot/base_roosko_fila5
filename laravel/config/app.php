<?php

use App\Providers\AppServiceProvider;
use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Auth\Passwords\PasswordResetServiceProvider;
use Illuminate\Broadcasting\BroadcastServiceProvider;
use Illuminate\Bus\BusServiceProvider;
use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Concurrency\ConcurrencyServiceProvider;
use Illuminate\Cookie\CookieServiceProvider;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Encryption\EncryptionServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider;
use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Notifications\NotificationServiceProvider;
use Illuminate\Pagination\PaginationServiceProvider;
use Illuminate\Pipeline\PipelineServiceProvider;
use Illuminate\Queue\QueueServiceProvider;
use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;
use Illuminate\Validation\ValidationServiceProvider;
use Illuminate\View\ViewServiceProvider;
use Laravel\Folio\FolioServiceProvider;
use Livewire\LivewireServiceProvider;
use Livewire\Volt\VoltServiceProvider;
use Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider;
use Modules\Activity\Providers\ActivityServiceProvider;
use Modules\AI\Providers\AIServiceProvider;
use Modules\Blog\Providers\BlogServiceProvider;
use Modules\Cms\Providers\CmsServiceProvider;
use Modules\Comment\Providers\CommentServiceProvider;
use Modules\Fixcity\Providers\FixcityServiceProvider;
use Modules\Gdpr\Providers\GdprServiceProvider;
use Modules\Geo\Providers\GeoServiceProvider;
use Modules\Job\Providers\JobServiceProvider;
use Modules\Lang\Providers\LangServiceProvider;
use Modules\Media\Providers\MediaServiceProvider;
use Modules\Mobile\Providers\MobileServiceProvider;
use Modules\Notify\Providers\NotifyServiceProvider;
use Modules\Rating\Providers\RatingServiceProvider;
use Modules\Seo\Providers\SeoServiceProvider;
use Modules\Tenant\Providers\TenantServiceProvider;
use Modules\UI\Providers\UIServiceProvider;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Providers\XotServiceProvider;
use Native\Mobile\NativeServiceProvider;
use Nwidart\Modules\LaravelModulesServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Spatie\Translatable\TranslatableServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'it'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'it'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Runtime providers
    |--------------------------------------------------------------------------
    |
    | NativePHP removes bootstrap/cache from the device bundle and then runs
    | config:cache. Keep the framework/module runtime contract in the cached
    | configuration so deferred services (cache, database, views, CMS) remain
    | available after a cold boot. Laravel deduplicates these entries against
    | the generated web manifest.
    */
    'providers' => [
        AuthServiceProvider::class,
        BroadcastServiceProvider::class,
        BusServiceProvider::class,
        CacheServiceProvider::class,
        ConsoleSupportServiceProvider::class,
        ConcurrencyServiceProvider::class,
        CookieServiceProvider::class,
        DatabaseServiceProvider::class,
        EncryptionServiceProvider::class,
        FilesystemServiceProvider::class,
        FoundationServiceProvider::class,
        HashServiceProvider::class,
        MailServiceProvider::class,
        NotificationServiceProvider::class,
        PaginationServiceProvider::class,
        PasswordResetServiceProvider::class,
        PipelineServiceProvider::class,
        QueueServiceProvider::class,
        RedisServiceProvider::class,
        SessionServiceProvider::class,
        TranslationServiceProvider::class,
        ValidationServiceProvider::class,
        ViewServiceProvider::class,
        LivewireServiceProvider::class,
        VoltServiceProvider::class,
        FolioServiceProvider::class,
        LaravelLocalizationServiceProvider::class,
        NativeServiceProvider::class,
        LaravelModulesServiceProvider::class,
        LaravelDataServiceProvider::class,
        MediaLibraryServiceProvider::class,
        PermissionServiceProvider::class,
        TranslatableServiceProvider::class,
        AppServiceProvider::class,
        App\Providers\FolioServiceProvider::class,
        ActivityServiceProvider::class,
        AIServiceProvider::class,
        BlogServiceProvider::class,
        CmsServiceProvider::class,
        CommentServiceProvider::class,
        FixcityServiceProvider::class,
        GdprServiceProvider::class,
        GeoServiceProvider::class,
        JobServiceProvider::class,
        LangServiceProvider::class,
        MediaServiceProvider::class,
        MobileServiceProvider::class,
        NotifyServiceProvider::class,
        RatingServiceProvider::class,
        SeoServiceProvider::class,
        TenantServiceProvider::class,
        UIServiceProvider::class,
        UserServiceProvider::class,
        XotServiceProvider::class,
    ],

];
