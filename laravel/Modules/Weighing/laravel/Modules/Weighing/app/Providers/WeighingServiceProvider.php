<?php

declare(strict_types=1);

namespace Modules\Weighing\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;
use Override;

/**
 * Service Provider del modulo Weighing.
 *
 * `$module_dir` / `$module_ns` in snake case: sono i nomi letti da XotBaseServiceProvider.
 */
class WeighingServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Weighing';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    /**
     * Config sotto la chiave `weighing` (non `weighing.config`), come Activity.
     */
    #[Override]
    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->name, 'config/config.php') => config_path('weighing.php'),
        ], 'config');

        $this->mergeConfigFrom(module_path($this->name, 'config/config.php'), 'weighing');
    }
}
