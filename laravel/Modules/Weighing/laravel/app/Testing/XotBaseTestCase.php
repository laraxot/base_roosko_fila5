<?php

declare(strict_types=1);

namespace App\Testing;

use Illuminate\Foundation\Testing\TestCase as IlluminateTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesApplication;

/**
 * App-level test base (legacy shim).
 *
 * Module tests must extend Modules\Xot\Tests\XotBaseTestCase — not this class.
 * nWidart\Modules\Tests\BaseTestCase is not autoloaded in nwidart/laravel-modules v13.
 *
 * @see Modules\Xot\docs\wiki\memories\testcase-hierarchy-nwidart-dev-only.md
 */
abstract class XotBaseTestCase extends IlluminateTestCase
{
    use CreatesApplication;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }
}
