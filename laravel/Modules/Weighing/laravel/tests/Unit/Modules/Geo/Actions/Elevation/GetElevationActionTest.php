<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Geo\Actions\Elevation\GetElevationAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(GetElevationAction::class);
    Http::fake();
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('GetElevationAction', function () {
    it('returns elevation for single coordinate', function () {
        Http::fake([
            'api.open-elevation.com/api/v1/lookup*' => Http::response([
                'results' => [
                    ['latitude' => 45.4642, 'longitude' => 9.1900, 'elevation' => 122.5],
                ],
            ], 200),
        ]);

        $result = $this->action->execute(45.4642, 9.1900);

        expect($result)->toBeArray()
            ->toHaveKeys(['latitude', 'longitude', 'elevation']);
        expect($result['elevation'])->toBe(122.5);
    });

    it('returns elevations for multiple coordinates', function () {
        Http::fake([
            'api.open-elevation.com/api/v1/lookup*' => Http::response([
                'results' => [
                    ['latitude' => 45.4642, 'longitude' => 9.1900, 'elevation' => 122.5],
                    ['latitude' => 41.9028, 'longitude' => 12.4964, 'elevation' => 21.0],
                ],
            ], 200),
        ]);

        $coordinates = [
            ['lat' => 45.4642, 'lng' => 9.1900],
            ['lat' => 41.9028, 'lng' => 12.4964],
        ];

        $result = $this->action->execute($coordinates);

        expect($result)->toBeArray()->toHaveCount(2);
        expect($result[0]['elevation'])->toBe(122.5);
        expect($result[1]['elevation'])->toBe(21.0);
    });

    it('handles empty coordinates array', function () {
        $result = $this->action->execute([]);

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('throws exception for API error', function () {
        Http::fake([
            'api.open-elevation.com/api/v1/lookup*' => Http::response(null, 500),
        ]);

        expect(fn () => $this->action->execute(45.4642, 9.1900))
            ->toThrow(Exception::class);
    });

    it('handles malformed response', function () {
        Http::fake([
            'api.open-elevation.com/api/v1/lookup*' => Http::response([
                'results' => [],
            ], 200),
        ]);

        $result = $this->action->execute(45.4642, 9.1900);

        expect($result)->toBeArray()->toBeEmpty();
    });
});
