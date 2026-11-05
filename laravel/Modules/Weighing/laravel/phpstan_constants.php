<?php

declare(strict_types=1);
use Pest\Expectation\Expectation;
use Pest\PendingCalls\TestCall;
use PHPUnit\Framework\SkippedWithMessageException;

// https://phpstan.org/user-guide/discovering-symbols

if (! defined('LARAVEL_DIR')) {
    define('LARAVEL_DIR', __DIR__);
}
// Constant LARAVEL_START already defined
// define('LARAVEL_START', microtime(true));

// Include PHPStan stubs for optional third-party packages not installed at root level.
// require_once __DIR__.'/.phpstan-stubs/morph-to-one.stub';
// require_once __DIR__.'/.phpstan-stubs/laravel-pdf.stub';

if (! function_exists('skip')) {
    /**
     * @return never
     */
    function skip(string $message = ''): void
    {
        throw new SkippedWithMessageException($message);
    }
}

if (! function_exists('typedMock')) {
    /**
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return T
     */
    function typedMock(string $class): object
    {
        /** @var T */
        return Mockery::mock($class);
    }
}

// Pest DSL stubs for PHPStan
if (! function_exists('expect')) {
    /**
     * @template T
     *
     * @param  T  $value
     * @return Expectation<T>
     */
    function expect($value)
    {
        // This is a stub for PHPStan — the real implementation is in Pest
        throw new RuntimeException('This is a Pest stub for PHPStan analysis only');
    }
}

if (! function_exists('it')) {
    /**
     * @param  callable(): void|null  $closure
     * @return TestCall
     */
    function it(string $description, ?callable $closure = null)
    {
        // This is a stub for PHPStan — the real implementation is in Pest
        throw new RuntimeException('This is a Pest stub for PHPStan analysis only');
    }
}

if (! function_exists('uses')) {
    /**
     * @param  array<string, mixed>|string  $traits
     */
    function uses($traits): void
    {
        // This is a stub for PHPStan — the real implementation is in Pest
        throw new RuntimeException('This is a Pest stub for PHPStan analysis only');
    }
}

if (! function_exists('describe')) {
    /**
     * @param  callable(): void  $closure
     */
    function describe(string $description, callable $closure): void
    {
        // This is a stub for PHPStan — the real implementation is in Pest
        throw new RuntimeException('This is a Pest stub for PHPStan analysis only');
    }
}
