<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Datas\CoordinatesData;
use Modules\Geo\Datas\GeocodingData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('GeocodingData DTO', function () {
    it('creates with all fields', function () {
        $dto = new GeocodingData(
            placeId: 'place-123',
            formattedAddress: 'Via Roma 1, 20100 Milano MI, Italia',
            coordinates: new CoordinatesData(45.4642, 9.1900),
            addressComponents: [
                'street' => 'Via Roma',
                'city' => 'Milano',
                'province' => 'MI',
                'postal_code' => '20100',
                'country' => 'Italia',
            ],
            placeType: 'street_address',
            confidence: 0.95,
        );

        expect($dto->placeId)->toBe('place-123');
        expect($dto->formattedAddress)->toBe('Via Roma 1, 20100 Milano MI, Italia');
        expect($dto->coordinates)->toBeInstanceOf(CoordinatesData::class);
        expect($dto->addressComponents['city'])->toBe('Milano');
        expect($dto->placeType)->toBe('street_address');
        expect($dto->confidence)->toBe(0.95);
    });

    it('creates with minimal fields', function () {
        $dto = new GeocodingData(
            placeId: 'place-123',
            formattedAddress: 'Milano, Italia',
        );

        expect($dto->placeId)->toBe('place-123');
        expect($dto->coordinates)->toBeNull();
        expect($dto->confidence)->toBe(1.0); // default
    });

    it('creates from API response array', function () {
        $apiResponse = [
            'place_id' => 'api-123',
            'formatted_address' => 'Test Address',
            'geometry' => [
                'location' => ['lat' => 45.0, 'lng' => 9.0],
            ],
            'address_components' => [],
            'types' => ['locality'],
        ];

        $dto = GeocodingData::fromApiResponse($apiResponse);

        expect($dto->placeId)->toBe('api-123');
        expect($dto->coordinates->latitude)->toBe(45.0);
    });

    it('converts to array', function () {
        $dto = new GeocodingData(
            placeId: 'test',
            formattedAddress: 'Test',
            coordinates: new CoordinatesData(45.0, 9.0),
        );
        $array = $dto->toArray();

        expect($array)->toHaveKeys(['place_id', 'formatted_address', 'coordinates']);
    });
});
