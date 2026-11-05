<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Datas\RouteData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('RouteData DTO', function () {
    it('creates with all fields', function () {
        $dto = new RouteData(
            distance: 570000, // meters
            duration: 19800,  // seconds
            polyline: 'encoded_polyline_string',
            legs: [
                ['distance' => 10000, 'duration' => 1200, 'start_location' => ['lat' => 45.4642, 'lng' => 9.1900]],
                ['distance' => 15000, 'duration' => 1800, 'start_location' => ['lat' => 44.4949, 'lng' => 11.3426]],
            ],
            waypointOrder: [1, 0],
            travelMode: 'driving',
        );

        expect($dto->distance)->toBe(570000);
        expect($dto->duration)->toBe(19800);
        expect($dto->polyline)->toBe('encoded_polyline_string');
        expect($dto->legs)->toHaveCount(2);
        expect($dto->waypointOrder)->toBe([1, 0]);
        expect($dto->travelMode)->toBe('driving');
    });

    it('gets formatted distance', function () {
        $dto = new RouteData(
            distance: 570000,
            duration: 19800,
        );

        expect($dto->getFormattedDistance())->toBe('570 km');
    });

    it('gets formatted duration', function () {
        $dto = new RouteData(
            distance: 570000,
            duration: 19800, // 5h 30m
        );

        expect($dto->getFormattedDuration())->toBe('5h 30m');
    });

    it('converts to array', function () {
        $dto = new RouteData(
            distance: 1000,
            duration: 600,
        );
        $array = $dto->toArray();

        expect($array)->toHaveKeys(['distance', 'duration', 'formatted_distance', 'formatted_duration']);
    });
});
