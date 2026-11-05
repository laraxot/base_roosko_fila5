<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\FolioServiceProvider;
use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Auth\Passwords\PasswordResetServiceProvider;
use Illuminate\Broadcasting\BroadcastServiceProvider;
use Illuminate\Bus\BusServiceProvider;
use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Cookie\CookieServiceProvider;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Encryption\EncryptionServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
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
use Laravel\Boost\BoostServiceProvider;
use Laravel\Mcp\Server\McpServiceProvider;
use Modules\Cms\Providers\CmsServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    FolioServiceProvider::class,
    AuthServiceProvider::class,
    PasswordResetServiceProvider::class,
    BroadcastServiceProvider::class,
    BusServiceProvider::class,
    CacheServiceProvider::class,
    CookieServiceProvider::class,
    DatabaseServiceProvider::class,
    EncryptionServiceProvider::class,
    FilesystemServiceProvider::class,
    HashServiceProvider::class,
    MailServiceProvider::class,
    NotificationServiceProvider::class,
    PaginationServiceProvider::class,
    PipelineServiceProvider::class,
    QueueServiceProvider::class,
    RedisServiceProvider::class,
    SessionServiceProvider::class,
    TranslationServiceProvider::class,
    ValidationServiceProvider::class,
    ViewServiceProvider::class,
    CmsServiceProvider::class,
    // Pacchetti require-dev: con `composer install --no-dev` la classe non esiste e il boot cadrebbe (F-01, DCP-282).
    ...array_filter([BoostServiceProvider::class, McpServiceProvider::class], class_exists(...)),
];
