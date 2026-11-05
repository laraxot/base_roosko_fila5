<?php

declare(strict_types=1);

namespace Tests\Modules\Mobile;

use Illuminate\Foundation\Application;
use Modules\Mobile\Providers\MobileServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case for Mobile module.
 *
 * Scopo: bootstrap Pest/PHPUnit del modulo Mobile (Nwidart),
 * registrando il provider del modulo sulla catena XotBaseTestCase.
 */
abstract class TestCase extends XotBaseTestCase
{
    /**
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            MobileServiceProvider::class,
        ];
    }
}
