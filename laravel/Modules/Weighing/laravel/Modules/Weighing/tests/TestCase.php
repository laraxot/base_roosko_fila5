<?php

declare(strict_types=1);

namespace Modules\Weighing\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Weighing\Providers\WeighingServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;
use Modules\Xot\Tests\XotModuleSchema;

/**
 * Base dei test Weighing, come Activity: sqlite condiviso, nessun migrate:fresh, transazione per test.
 * Richiede la connessione `weighing` in config/database.php del progetto.
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['sqlite', 'weighing'];

    protected function setUp(): void
    {
        $this->prepareSharedSqliteForTesting();
        XotModuleSchema::ensure('Weighing');

        parent::setUp();
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            WeighingServiceProvider::class,
        ];
    }
}
