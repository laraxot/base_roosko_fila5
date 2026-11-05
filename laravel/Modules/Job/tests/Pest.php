<?php

declare(strict_types=1);
<<<<<<< HEAD
use Modules\Job\Database\Factories\JobBatchFactory;
use Modules\Job\Database\Factories\JobFactory;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobBatch;

/*
 * Bootstrap Pest — modulo Job.
 * Ogni file test dichiara uses(Modules\Job\Tests\TestCase::class).
 * Vietato pest()->extend() / expect()->extend() (PHPStan method.internalClass).
 */

/**
 * @param array<string, mixed> $attributes
 */
function createJob(array $attributes = []): Job
{
    return JobFactory::new()->createOne($attributes);
}

/**
 * @param array<string, mixed> $attributes
 */
function makeJob(array $attributes = []): Job
{
    $job = JobFactory::new()->make($attributes);
    if (! $job instanceof Job) {
        throw new RuntimeException('Expected Job model from factory');
    }

    return $job;
}

/**
 * @param array<string, mixed> $attributes
 */
function createJobBatch(array $attributes = []): JobBatch
{
    return JobBatchFactory::new()->createOne($attributes);
}

/**
 * @param array<string, mixed> $attributes
 */
function makeJobBatch(array $attributes = []): JobBatch
{
    $batch = JobBatchFactory::new()->make($attributes);
    if (! $batch instanceof JobBatch) {
        throw new RuntimeException('Expected JobBatch model from factory');
    }

    return $batch;
}
=======

/*
 * Bootstrap Pest — modulo Job.
 *
 * Questo file NON viene caricato. `Pest\Bootstrappers\BootFiles` legge `Pest.php`,
 * `Helpers.php` ed `Expectations.php` da un solo percorso per run — quello della root —
 * quindi ogni funzione dichiarata qui è codice morto e i test che la chiamano falliscono
 * con `Call to undefined function`.
 *
 * Regole, non negoziabili:
 * - zero funzioni libere qui dentro (`grep -c '^function ' ` deve dare 0);
 * - helper condivisi: metodi statici su `Modules\Xot\Tests\XotBasePest` (autoload PSR-4,
 *   niente `require_once`);
 * - helper di dominio: metodi statici su `Modules\Job\Tests\TestCase`;
 * - ogni file di test dichiara `uses(\Modules\Job\Tests\TestCase::class)` in testa —
 *   un `uses()->in(...)` scritto qui non verrebbe applicato;
 * - vietati `pest()->extend()` e `pest()->uses()` (PHPStan `method.internalClass`);
 * - vietata la cartella `tests/Support/` per helper Pest (ADR-002).
 */
>>>>>>> laraxot/dev
