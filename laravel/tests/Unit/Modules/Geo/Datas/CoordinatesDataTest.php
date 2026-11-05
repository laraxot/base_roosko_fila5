<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Datas\CoordinatesData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('CoordinatesData DTO', function () {
    it('creates from latitude and longitude', function () {
        $dto = new CoordinatesData(45.4642, 9.1900);

        expect($dto->latitude)->toBe(45.4642);
        expect($dto->longitude)->toBe(9.1900);
    });

    it('creates from array', function () {
        $dto = CoordinatesData::fromArray([
            'latitude' => 41.9028,
            'longitude' => 12.4964,
        ]);

        expect($dto->latitude)->toBe(41.9028);
        expect($dto->longitude)->toBe(12.4964);
    });

    it('creates from array with lat/lng keys', function () {
        $dto = CoordinatesData::fromArray([
            'lat' => 40.8518,
            'lng' => 14.2681,
        ]);

        expect($dto->latitude)->toBe(40.8518);
        expect($dto->longitude)->toBe(14.2681);
    });

    it('converts to array', function () {
        $dto = new CoordinatesData(45.4642, 9.1900);
        $array = $dto->toArray();

        expect($array)->toBeArray()
            ->toHaveKeys(['latitude', 'longitude']);
        expect($array['latitude'])->toBe(45.4642);
    });

    it('converts to lat/lng array', function () {
        $dto = new CoordinatesData(45.4642, 9.1900);
        $array = $dto->toLatLngArray();

        expect($array)->toBeArray()
            ->toHaveKeys(['lat', 'lng']);
        expect($array['lat'])->toBe(45.4642);
    });

    it('validates latitude range', function () {
        expect(fn () => new CoordinatesData(95, 9.0))
            ->toThrow(InvalidArgumentException::class, 'Latitude must be between -90 and 90');

        expect(fn () => new CoordinatesData(-95, 9.0))
            ->toThrow(InvalidArgumentException::class);
    });

    it('validates longitude range', function () {
        expect(fn () => new CoordinatesData(45.0, 185))
            ->toThrow(InvalidArgumentException::class, 'Longitude must be between -180 and 180');

        expect(fn () => new CoordinatesData(45.0, -185))
            ->toThrow(InvalidArgumentException::class);
    });

    it('accepts edge values', function () {
        $dto1 = new CoordinatesData(-90, -180);
        expect($dto1->latitude)->toBe(-90);
        expect($dto1->longitude)->toBe(-180);

        $dto2 = new CoordinatesData(90, 180);
        expect($dto2->latitude)->toBe(90);
        expect($dto2->longitude)->toBe(180);
    });

    it('calculates distance to another CoordinatesData', function () {
        $milano = new CoordinatesData(45.4642, 9.1900);
        $roma = new CoordinatesData(41.9028, 12.4964);

        $distance = $milano->distanceTo($roma);

        expect($distance)->toBeFloat()->toBeGreaterThan(0);
        expect($distance)->toBeGreaterThan(400000); // ~477km in meters
    });

    it('checks equality', function () {
        $coord1 = new CoordinatesData(45.4642, 9.1900);
        $coord2 = new CoordinatesData(45.4642, 9.1900);
        $coord3 = new CoordinatesData(41.9028, 12.4964);

        expect($coord1->equals($coord2))->toBeTrue();
        expect($coord1->equals($coord3))->toBeFalse();
    });

    it('formats as string', function () {
        $dto = new CoordinatesData(45.4642, 9.1900);

        expect($dto->toString())->toBe('45.4642, 9.1900');
        expect((string) $dto)->toBe('45.4642, 9.1900');
    });
});
