<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Models;

use Modules\Geo\Models\Comune;
use Modules\Geo\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/*
 * Specifica neutralizzata: i 16 scenari di questa prima stesura vivono ora in ComuneModelTest.php.
 *
 * Questo file era una classe PHPUnit che chiamava sul modello Comune i named scope byRegion(), byProvince(),
 * byCap(), byName(), byExactName() e le loro combinazioni. Quei metodi non esistono su Comune e non sono mai
 * esistiti: `git log --all -S'function byRegion' -- app/Models/Comune.php` e vuoto. Solo byRegion(), byProvince()
 * e byCap() esistono, come metodi statici di ComuneJson (che restituisce collection di array, non un builder).
 * I 69 commenti di ignore PHPStan della vecchia stesura coprivano proprio questo.
 *
 * Gli stessi comportamenti sono provati da ComuneModelTest.php con le API reali di Comune (query Eloquent,
 * findByCap(), findByNome()) e le letture del JSON da comunetest.php. Il corpo vecchio resta in git
 * (blob fafb1aa9 in HEAD, commit 60524329; blob 5e25f92a in laraxot/dev).
 *
 * Il file minuscolo comunetest.php (non raccolto da Pest per il suffisso) e' stato fuso qui:
 * i test "Comune read APIs" sotto coprono findByNome(), findByCap(), getComuniByProvincia()
 * e findComune(), le ultime due senza altra copertura.
 */

describe('Comune query scopes (first draft)', function (): void {
    it('filters comuni by region, province, cap and name with the by*() scopes')
        ->skip('Comune::byRegion() and the other by*() scopes not implemented');

    it('loads, creates, updates and deletes a comune')
        ->skip('superseded by ComuneModelTest.php');
});

describe('Comune read APIs', function (): void {
    test('finds a comune by name without case sensitivity', function (): void {
        $comune = Comune::findByNome('milano');

        Assert::assertInstanceOf(Comune::class, $comune);
        Assert::assertSame('Milano', $comune->nome);
    });

    test('finds comuni by postal code', function (): void {
        $comuni = Comune::findByCap('20100');

        Assert::assertCount(1, $comuni);
        $comune = $comuni->first();
        Assert::assertInstanceOf(Comune::class, $comune);
        Assert::assertSame('Milano', $comune->nome);
    });

    test('lists comuni for a province', function (): void {
        $comuni = Comune::getComuniByProvincia('Palermo');

        Assert::assertNotEmpty($comuni);
        foreach ($comuni as $comune) {
            Assert::assertInstanceOf(Comune::class, $comune);
            Assert::assertSame('Palermo', $comune->provincia);
        }
    });

    test('finds a comune record by numeric id', function (): void {
        $comune = Comune::findComune(1);

        Assert::assertIsArray($comune);
        Assert::assertSame('Palermo', $comune['nome'] ?? null);
    });
});
