<?php

declare(strict_types=1);

use function Safe\file_get_contents;
use function Safe\glob;
use function Safe\preg_match;

/*
 * DCP-281 (A-36): `CreateTicketScreen.php` dichiarava `class TicketDetailScreen`, la stessa classe di `TicketDetailScreen.php`.
 * Con PSR-4 `CreateTicketScreen` non esisteva e le due dichiarazioni si scontravano appena entrambe i file venivano caricati.
 * La lettura e' statica (nessun autoload) proprio per non provocare il conflitto che si vuole rilevare.
 */

/**
 * @return array<string, array{string}> nome file => [percorso]
 */
function dcp281NativeScreenFiles(): array
{
    $dataset = [];

    foreach (glob(dirname(__DIR__, 2).'/app/Native/Screens/*.php') as $path) {
        if (is_string($path)) {
            $dataset[basename($path)] = [$path];
        }
    }

    return $dataset;
}

function dcp281DeclaredClass(string $path): ?string
{
    $found = [];

    return preg_match('/^(?:final\s+|abstract\s+)?class\s+(\w+)/m', file_get_contents($path), $found) === 1
        ? ($found[1] ?? null)
        : null;
}

it('declares in each native screen file the class that carries the file name', function (string $path): void {
    expect(dcp281DeclaredClass($path))->toBe(basename($path, '.php'));
})->with(dcp281NativeScreenFiles());

it('never declares the same screen class twice', function (): void {
    $declared = array_map(
        static fn (array $file): ?string => dcp281DeclaredClass($file[0]),
        array_values(dcp281NativeScreenFiles()),
    );

    expect(array_unique($declared))->toHaveCount(count($declared));
});
