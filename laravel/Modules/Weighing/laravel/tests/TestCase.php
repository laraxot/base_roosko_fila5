<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\User\Models\User;
use Modules\Xot\Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions {
        beginDatabaseTransaction as openDatabaseTransactions;
    }

    /** @var list<string> */
    protected array $connectionsToTransact = ['mariadb', 'user', 'fixcity', 'blog'];

    public static bool $migrationsRun = false;

    /** Shared module schemas are migrated once; transactions isolate each test. */
    public function refreshDatabase(): void {}

    protected function setUp(): void
    {
        parent::setUp();

        config(['auth.providers.users.model' => User::class]);
    }

    /**
     * Migrate before the transactions open: in MySQL every DDL statement commits implicitly,
     * so a migrate inside the transaction would leave the first test's rows in the DB.
     * No native return type: subclasses that use DatabaseTransactions again redeclare the untyped trait method.
     *
     * @return void
     */
    public function beginDatabaseTransaction()
    {
        if (! static::$migrationsRun) {
            $this->runTestingMigrations();
            static::$migrationsRun = true;
        }

        $this->openDatabaseTransactions();
    }

    protected function runTestingMigrations(): void
    {
        $this->withoutMockingConsoleOutput();
        $this->realArtisan('migrate', ['--force' => true]);
    }
}
