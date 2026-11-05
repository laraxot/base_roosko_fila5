<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Models\Province;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('Province Model', function () {
    it('creates a province with required fields', function () {
        $province = Province::create([
            'name' => 'Milano',
            'code' => 'MI',
            'region_code' => '03',
        ]);

        expect($province)->toBeInstanceOf(Province::class);
        expect($province->name)->toBe('Milano');
        expect($province->code)->toBe('MI');
        expect($province->region_code)->toBe('03');
    });

    it('has fillable fields', function () {
        $fillable = (new Province)->getFillable();

        expect($fillable)->toContain('name');
        expect($fillable)->toContain('code');
        expect($fillable)->toContain('region_code');
    });

    it('belongs to region', function () {
        $province = Province::factory()->create();

        expect($province->region())->toBeInstanceOf(BelongsTo::class);
    });

    it('has many comuni', function () {
        $province = Province::factory()->create();

        expect($province->comuni())->toBeInstanceOf(HasMany::class);
    });

    it('code is unique', function () {
        Province::factory()->create(['code' => 'MI', 'name' => 'Milano']);

        expect(fn () => Province::factory()->create(['code' => 'MI', 'name' => 'Milano 2']))
            ->toThrow(QueryException::class);
    });
});
