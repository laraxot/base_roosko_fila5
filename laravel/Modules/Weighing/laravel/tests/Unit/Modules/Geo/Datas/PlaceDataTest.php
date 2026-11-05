<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Datas\CoordinatesData;
use Modules\Geo\Datas\PlaceData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('PlaceData DTO', function () {
    it('creates with all fields', function () {
        $dto = new PlaceData(
            placeId: 'place-123',
            name: 'Duomo di Milano',
            formattedAddress: 'Piazza del Duomo, 20121 Milano MI, Italia',
            coordinates: new CoordinatesData(45.4642, 9.1900),
            placeTypes: ['tourist_attraction', 'point_of_interest', 'establishment'],
            rating: 4.8,
            userRatingsTotal: 150000,
            openingHours: ['open_now' => true, 'weekday_text' => []],
            photos: [],
        );

        expect($dto->placeId)->toBe('place-123');
        expect($dto->name)->toBe('Duomo di Milano');
        expect($dto->placeTypes)->toContain('tourist_attraction');
        expect($dto->rating)->toBe(4.8);
        expect($dto->userRatingsTotal)->toBe(150000);
        expect($dto->openingHours['open_now'])->toBeTrue();
    });

    it('creates with minimal fields', function () {
        $dto = new PlaceData(
            placeId: 'place-123',
            name: 'Test Place',
        );

        expect($dto->placeId)->toBe('place-123');
        expect($dto->name)->toBe('Test Place');
        expect($dto->rating)->toBeNull();
    });

    it('checks if open now', function () {
        $openDto = new PlaceData(
            placeId: '1',
            name: 'Open Place',
            openingHours: ['open_now' => true],
        );
        $closedDto = new PlaceData(
            placeId: '2',
            name: 'Closed Place',
            openingHours: ['open_now' => false],
        );
        $unknownDto = new PlaceData(
            placeId: '3',
            name: 'Unknown Place',
        );

        expect($openDto->isOpenNow())->toBeTrue();
        expect($closedDto->isOpenNow())->toBeFalse();
        expect($unknownDto->isOpenNow())->toBeNull();
    });

    it('converts to array', function () {
        $dto = new PlaceData(
            placeId: 'test',
            name: 'Test',
            coordinates: new CoordinatesData(45.0, 9.0),
        );
        $array = $dto->toArray();

        expect($array)->toHaveKeys(['place_id', 'name', 'coordinates']);
    });
});
