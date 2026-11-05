<?php

declare(strict_types=1);

namespace Modules\Mobile\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

/**
 * Mobile RouteServiceProvider — extends XotBaseRouteServiceProvider.
 *
 * @see BMAD STORY-410 · Issue #429 · Discussion #669
 * @see XotBaseRouteServiceProvider: Modules\Xot\Providers\XotBaseRouteServiceProvider
 */
class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Mobile';

    protected string $moduleNamespace = 'Modules\Mobile\app\Http\Controllers';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
