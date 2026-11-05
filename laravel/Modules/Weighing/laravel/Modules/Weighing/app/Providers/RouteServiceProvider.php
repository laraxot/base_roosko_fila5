<?php

declare(strict_types=1);

namespace Modules\Weighing\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Weighing';

    protected string $moduleNamespace = 'Modules\Weighing\Http\Controllers';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
