<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Models\Comune;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('Comune Model', function () {
    it('creates a comune with required fields', function () {
        $comune = Comune::create([
            'name' => 'Milano',
            'cap' => '20100',
            'province_code' => 'MI',
            'region_code' => '03',
            'latitude' => 45.4642,
            'longitude' => 9.1900,
        ]);

        expect($comune)->toBeInstanceOf(Comune::class);
        expect($comune->name)->toBe('Milano');
        expect($comune->cap)->toBe('20100');
        expect($comune->province_code)->toBe('MI');
        expect($comune->region_code)->toBe('03');
    });

    it('has fillable fields', function () {
        $fillable = (new Comune)->getFillable();

        expect($fillable)->toContain('name');
        expect($fillable)->toContain('cap');
        expect($fillable)->toContain('province_code');
        expect($fillable)->toContain('region_code');
        expect($fillable)->toContain('latitude');
        expect($fillable)->toContain('longitude');
    });

    it('casts latitude and longitude to float', function () {
        $comune = Comune::create([
            'name' => 'Test',
            'cap' => '12345',
            'province_code' => 'TS',
            'region_code' => '99',
            'latitude' => '45.123456',
            'longitude' => '9.654321',
        ]);

        expect($comune->latitude)->toBeFloat()->toBe(45.123456);
        expect($comune->longitude)->toBeFloat()->toBe(9.654321);
    });

    it('belongs to province relationship', function () {
        $comune = Comune::factory()->create();

        expect($comune->province())->toBeInstanceOf(BelongsTo::class);
    });

    it('belongs to region relationship', function () {
        $comune = Comune::factory()->create();

        expect($comune->region())->toBeInstanceOf(BelongsTo::class);
    });

    it('scopes by province', function () {
        Comune::factory()->create(['province_code' => 'MI', 'name' => 'Milano']);
        Comune::factory()->create(['province_code' => 'RO', 'name' => 'Roma']);

        $milanoComuni = Comune::where('province_code', 'MI')->get();

        expect($milanoComuni)->toHaveCount(1);
        expect($milanoComuni->first()->name)->toBe('Milano');
    });

    it('scopes by region', function () {
        Comune::factory()->create(['region_code' => '03', 'name' => 'Milano']);
        Comune::factory()->create(['region_code' => '12', 'name' => 'Roma']);

        $lombardiaComuni = Comune::where('region_code', '03')->get();

        expect($lombardiaComuni)->toHaveCount(1);
    });

    it('has unique CAP per comune', function () {
        Comune::factory()->create(['cap' => '20100', 'name' => 'Milano']);

        expect(fn () => Comune::factory()->create(['cap' => '20100', 'name' => 'Milano 2']))
            ->toThrow(QueryException::class);
    });
});
