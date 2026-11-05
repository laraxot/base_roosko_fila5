<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Geo\Actions\Elevation\FetchOpenElevationAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(FetchOpenElevationAction::class);
    Http::fake();
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('FetchOpenElevationAction', function () {
    it('fetches elevation from Open-Elevation API', function () {
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
    });

    it('batches multiple coordinates in single request', function () {
        Http::fake([
            'api.open-elevation.com/api/v1/lookup*' => Http::response([
                'results' => [
                    ['latitude' => 45.4642, 'longitude' => 9.1900, 'elevation' => 122.5],
                    ['latitude' => 41.9028, 'longitude' => 12.4964, 'elevation' => 21.0],
                    ['latitude' => 40.8518, 'longitude' => 14.2681, 'elevation' => 17.0],
                ],
            ], 200),
        ]);

        $coordinates = [
            ['lat' => 45.4642, 'lng' => 9.1900],
            ['lat' => 41.9028, 'lng' => 12.4964],
            ['lat' => 40.8518, 'lng' => 14.2681],
        ];

        $result = $this->action->execute($coordinates);

        expect($result)->toHaveCount(3);
    });

    it('handles rate limiting', function () {
        Http::fake([
            'api.open-elevation.com/api/v1/lookup*' => Http::response(null, 429),
        ]);

        expect(fn () => $this->action->execute(45.4642, 9.1900))
            ->toThrow(Exception::class);
    });

    it('handles network timeout', function () {
        Http::fake([
            'api.open-elevation.com/api/v1/lookup*' => Http::response(null, 504),
        ]);

        expect(fn () => $this->action->execute(45.4642, 9.1900))
            ->toThrow(Exception::class);
    });
});
