<?php

declare(strict_types=1);

use function Safe\file_get_contents;

/**
 * Il layout `app` e' condiviso da progetti con moduli diversi: nessun componente di modulo
 * scritto direttamente (`<x-predict::...>`), altrimenti il layout non compila dove il modulo manca.
 */
test('il layout app non usa componenti di modulo scritti direttamente', function (): void {
    $html = (string) file_get_contents(dirname(__DIR__, 2).'/resources/views/components/layouts/app.blade.php');

    expect($html)->not->toMatch('/<x-(predict|fixcity|passaparola)::/');
    expect($html)->toContain('ResolveAvailableComponentsAction');
    expect($html)->toContain('<x-dynamic-component');
});

test('i blocchi usati dalla homepage di base non dipendono da moduli', function (string $block): void {
    $html = (string) file_get_contents(dirname(__DIR__, 2).'/resources/views/components/blocks/'.$block.'.blade.php');

    // filament e' una dipendenza del tema (composer.json), non un modulo facoltativo
    expect($html)->not->toMatch('/<x-(?!filament::)[a-z]+::/');
    expect($html)->not->toContain('Modules\\');
})->with(['hero/simple', 'features/simple', 'cta/simple']);
