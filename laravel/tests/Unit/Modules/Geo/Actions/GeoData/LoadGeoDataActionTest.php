<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Modules\Geo\Actions\GeoData\LoadGeoDataAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(LoadGeoDataAction::class);
});

describe('LoadGeoDataAction', function () {
    it('loads geo data from JSON file with correct structure', function () {
        $mockData = [
            'regions' => [
                [
                    'name' => 'Lombardia',
                    'code' => '03',
                    'provinces' => [
                        [
                            'name' => 'Milano',
                            'code' => 'MI',
                            'cities' => [
                                ['name' => 'Milano', 'code' => '20100'],
                                ['name' => 'Sesto San Giovanni', 'code' => '20099'],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Lazio',
                    'code' => '12',
                    'provinces' => [
                        [
                            'name' => 'Roma',
                            'code' => 'RM',
                            'cities' => [
                                ['name' => 'Roma', 'code' => '00100'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        File::shouldReceive('exists')
            ->with(base_path('Modules/Geo/resources/json/comuni.json'))
            ->andReturnTrue();

        File::shouldReceive('get')
            ->with(base_path('Modules/Geo/resources/json/comuni.json'))
            ->andReturn(json_encode($mockData));

        $result = $this->action->execute();

        expect($result)->toBeInstanceOf(Collection::class);
        expect($result)->toHaveCount(2);
        expect($result->first())->toHaveKeys(['name', 'code', 'provinces']);
    });

    it('returns collection with regions', function () {
        $mockData = [
            'regions' => [
                ['name' => 'Test Region', 'code' => 'TR', 'provinces' => []],
            ],
        ];

        File::shouldReceive('exists')->andReturnTrue();
        File::shouldReceive('get')->andReturn(json_encode($mockData));

        $result = $this->action->execute();

        expect($result->first()['name'])->toBe('Test Region');
    });

    it('each region has provinces', function () {
        $mockData = [
            'regions' => [
                [
                    'name' => 'Lombardia',
                    'code' => '03',
                    'provinces' => [
                        ['name' => 'Milano', 'code' => 'MI', 'cities' => []],
                    ],
                ],
            ],
        ];

        File::shouldReceive('exists')->andReturnTrue();
        File::shouldReceive('get')->andReturn(json_encode($mockData));

        $result = $this->action->execute();

        $result->each(function ($region) {
            expect($region)->toHaveKey('provinces');
            expect($region['provinces'])->toBeArray();
        });
    });

    it('each province has cities', function () {
        $mockData = [
            'regions' => [
                [
                    'name' => 'Lombardia',
                    'code' => '03',
                    'provinces' => [
                        [
                            'name' => 'Milano',
                            'code' => 'MI',
                            'cities' => [['name' => 'Milano', 'code' => '20100']],
                        ],
                    ],
                ],
            ],
        ];

        File::shouldReceive('exists')->andReturnTrue();
        File::shouldReceive('get')->andReturn(json_encode($mockData));

        $result = $this->action->execute();

        $result->each(function ($region) {
            collect($region['provinces'])->each(function ($province) {
                expect($province)->toHaveKey('cities');
                expect($province['cities'])->toBeArray();
            });
        });
    });

    it('throws exception if JSON file missing', function () {
        File::shouldReceive('exists')->andReturnFalse();

        expect(fn () => $this->action->execute())
            ->toThrow(RuntimeException::class, 'Il file JSON dei comuni non esiste');
    });

    it('throws exception for invalid JSON structure', function () {
        File::shouldReceive('exists')->andReturnTrue();
        File::shouldReceive('get')->andReturn('invalid json');

        expect(fn () => $this->action->execute())
            ->toThrow(RuntimeException::class, 'Il file JSON dei comuni non è valido');
    });

    it('throws exception for missing regions key', function () {
        File::shouldReceive('exists')->andReturnTrue();
        File::shouldReceive('get')->andReturn(json_encode(['provinces' => []]));

        expect(fn () => $this->action->execute())
            ->toThrow(RuntimeException::class, 'Regioni mancanti nel file JSON');
    });

    it('throws exception for non-array regions', function () {
        File::shouldReceive('exists')->andReturnTrue();
        File::shouldReceive('get')->andReturn(json_encode(['regions' => 'not-array']));

        expect(fn () => $this->action->execute())
            ->toThrow(RuntimeException::class, 'Regioni mancanti nel file JSON');
    });

    it('throws exception when integrity check fails', function () {
        // Missing city code - should fail integrity
        $mockData = [
            'regions' => [
                ['name' => 'Lombardia', 'code' => '03', 'provinces' => []],
            ],
        ];

        File::shouldReceive('exists')->andReturnTrue();
        File::shouldReceive('get')->andReturn(json_encode($mockData));

        expect(fn () => $this->action->execute())
            ->toThrow(RuntimeException::class, 'Il file JSON dei comuni non è valido');
    });
});
