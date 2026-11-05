<?php

declare(strict_types=1);

<<<<<<< HEAD
use Modules\User\Models\Profile;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use Pest\Expectation;

/*
 * Il TestCase si lega per file: ogni test dichiara `uses(TestCase::class)` (136 file su 156,
 * alcuni con `->group('no-user-db')`). Un `pest()->extend()->in('Feature', 'Unit')` qui
 * collide con quei file (TestCaseAlreadyInUse) e blocca l'intera raccolta.
 */

/*
 * |--------------------------------------------------------------------------
 * | Expectations
 * |--------------------------------------------------------------------------
 * |
 * | When you're writing tests, you often need to check that values meet certain conditions. The
 * | "expect()" function gives you access to a set of "expectations" methods that you can use
 * | to assert different things. Of course, you may extend the Expectation API at any time.
 * |
 */

// L'estensione vale per qualunque valore: `$this` e' un Expectation sul valore sotto test, non ancora noto.
expect()->extend('toBeUser', function (): Expectation {
    /** @var Expectation<mixed> $this */
    return $this->toBeInstanceOf(User::class);
});

expect()->extend('toBeTeam', function (): Expectation {
    /** @var Expectation<mixed> $this */
    return $this->toBeInstanceOf(Team::class);
});

expect()->extend('toBeProfile', function (): Expectation {
    /** @var Expectation<mixed> $this */
    return $this->toBeInstanceOf(Profile::class);
});

/*
 * |--------------------------------------------------------------------------
 * | Functions
 * |--------------------------------------------------------------------------
 * |
 * | While Pest is very powerful out-of-the-box, you may have some testing code specific to your
 * | project that you don't want to repeat in every file. Here you can also expose helpers as
 * | global functions to help you to reduce the number of lines of code in your test files.
 * |
 */

// Helper globali (createUser, makeUser, createTeam, createProfile, ...): tests/Support/helpers.php,
// caricato da composer autoload-dev. Ridefinirli qui darebbe "Cannot redeclare" a runtime.
=======
/*
 * Bootstrap Pest — modulo User.
 *
 * Questo file NON viene caricato. `Pest\Bootstrappers\BootFiles` legge `Pest.php`,
 * `Helpers.php` ed `Expectations.php` da un solo percorso per run — quello della root —
 * quindi `expect()->extend()` e funzioni dichiarate qui sono codice morto.
 *
 * Regole, non negoziabili:
 * - il TestCase si lega per file: ogni test dichiara `uses(\Modules\User\Tests\TestCase::class)`
 *   (eventualmente con `->group(...)`); un `pest()->extend()->in(...)` qui colliderebbe
 *   con quei file (TestCaseAlreadyInUse) e bloccherebbe la raccolta;
 * - vietati `pest()->extend()`, `pest()->uses()` ed `expect()->extend()` in questo file;
 * - helper condivisi: `Modules\User\Tests\Support\helpers.php` (autoload-dev composer) o
 *   metodi statici su `Modules\User\Tests\TestCase` / `Modules\Xot\Tests\XotBasePest`;
 * - se servono expectation custom, vanno aggiunte nel `tests/Pest.php` ROOT, non qui.
 */
>>>>>>> laraxot/dev
