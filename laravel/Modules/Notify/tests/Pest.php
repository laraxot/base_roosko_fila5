<?php

declare(strict_types=1);

use Modules\Notify\Tests\TestCase;

/*
 * |--------------------------------------------------------------------------
 * | Test Case
 * |--------------------------------------------------------------------------
 * |
 * | Forma consigliata (XOT-5.41): un solo bind per modulo, nessun `uses()` nei file.
 * | Gli helper di test condivisi non vivono qui come funzioni globali (rischio di
 * | "cannot redeclare" nella run full-suite): sono metodi statici di
 * | `Modules\Notify\Tests\TestCase` e di `Modules\Xot\Tests\XotBasePest`.
 * |
 */

pest()->extend(TestCase::class)->in(__DIR__.'/Feature', __DIR__.'/Unit');
