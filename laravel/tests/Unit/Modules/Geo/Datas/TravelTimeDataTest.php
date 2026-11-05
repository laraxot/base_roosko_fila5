<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Datas\TravelTimeData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('TravelTimeData DTO', function () {
    it('creates with all fields', function () {
        $dto = new TravelTimeData(
            origin: 'Milano, Italia',
            destination: 'Roma, Italia',
            distance: 570000,
            duration: 19800,
            durationInTraffic: 22500,
            travelMode: 'driving',
            departureTime: time() + 3600,
        );

        expect($dto->origin)->toBe('Milano, Italia');
        expect($dto->destination)->toBe('Roma, Italia');
        expect($dto->distance)->toBe(570000);
        expect($dto->duration)->toBe(19800);
        expect($dto->durationInTraffic)->toBe(22500);
        expect($dto->travelMode)->toBe('driving');
    });

    it('creates with minimal fields', function () {
        $dto = new TravelTimeData(
            origin: 'A',
            destination: 'B',
            distance: 1000,
            duration: 600,
        );

        expect($dto->durationInTraffic)->toBeNull();
        expect($dto->travelMode)->toBe('driving'); // default
    });

    it('calculates traffic delay', function () {
        $dto = new TravelTimeData(
            origin: 'A',
            destination: 'B',
            distance: 10000,
            duration: 600,
            durationInTraffic: 900, // 50% longer
        );

        expect($dto->getTrafficDelay())->toBe(300); // seconds
        expect($dto->getTrafficDelayPercentage())->toBe(50.0);
    });

    it('returns zero delay if no traffic data', function () {
        $dto = new TravelTimeData(
            origin: 'A',
            destination: 'B',
            distance: 10000,
            duration: 600,
        );

        expect($dto->getTrafficDelay())->toBe(0);
        expect($dto->getTrafficDelayPercentage())->toBe(0.0);
    });

    it('gets formatted duration', function () {
        $dto = new TravelTimeData(
            origin: 'A',
            destination: 'B',
            distance: 1000,
            duration: 3661, // 1h 1m 1s
        );

        expect($dto->getFormattedDuration())->toBe('1h 1m');
    });

    it('converts to array', function () {
        $dto = new TravelTimeData(
            origin: 'A',
            destination: 'B',
            distance: 1000,
            duration: 600,
        );
        $array = $dto->toArray();

        expect($array)->toHaveKeys(['origin', 'destination', 'distance', 'duration', 'formatted_duration']);
    });
});
