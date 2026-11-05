<?php

declare(strict_types=1);

namespace Modules\Mobile\Datas;

use Modules\Xot\Datas\Concerns\MakesFromTenantConfig;
use Spatie\LaravelData\Data;

/** Tenant configuration contract for Mobile's settings entry point. */
final class MobileConfigData extends Data
{
    use MakesFromTenantConfig;

    public bool $enabled = true;
}
