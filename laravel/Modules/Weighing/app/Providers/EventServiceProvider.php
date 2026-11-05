<?php

declare(strict_types=1);

namespace Modules\Weighing\Providers;

use Modules\Xot\Providers\XotBaseEventServiceProvider;

/**
 * Registrato da XotBaseServiceProvider::register(): deve esistere anche senza listener.
 */
class EventServiceProvider extends XotBaseEventServiceProvider
{
    public string $name = 'Weighing';

    /** @var array<string, array<int, string>> */
    protected $listen = [];
}
