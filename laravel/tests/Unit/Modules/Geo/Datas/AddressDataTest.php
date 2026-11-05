<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Datas\AddressData;
use Modules\Geo\Datas\CoordinatesData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('AddressData DTO', function () {
    it('creates with all fields', function () {
        $dto = new AddressData(
            street: 'Via Roma 1',
            city: 'Milano',
            province: 'MI',
            postalCode: '20100',
            country: 'Italia',
            latitude: 45.4642,
            longitude: 9.1900,
        );

        expect($dto->street)->toBe('Via Roma 1');
        expect($dto->city)->toBe('Milano');
        expect($dto->province)->toBe('MI');
        expect($dto->postalCode)->toBe('20100');
        expect($dto->country)->toBe('Italia');
        expect($dto->latitude)->toBe(45.4642);
        expect($dto->longitude)->toBe(9.1900);
    });

    it('creates with minimal fields', function () {
        $dto = new AddressData(
            street: 'Via Roma 1',
            city: 'Milano',
        );

        expect($dto->street)->toBe('Via Roma 1');
        expect($dto->city)->toBe('Milano');
        expect($dto->province)->toBeNull();
        expect($dto->country)->toBe('Italia'); // default
    });

    it('creates from array', function () {
        $dto = AddressData::fromArray([
            'street' => 'Via Roma 1',
            'city' => 'Milano',
            'province' => 'MI',
            'postal_code' => '20100',
            'country' => 'Italia',
            'latitude' => 45.4642,
            'longitude' => 9.1900,
        ]);

        expect($dto->street)->toBe('Via Roma 1');
        expect($dto->postalCode)->toBe('20100');
    });

    it('converts to array', function () {
        $dto = new AddressData(
            street: 'Via Roma 1',
            city: 'Milano',
            province: 'MI',
            postalCode: '20100',
        );
        $array = $dto->toArray();

        expect($array)->toHaveKeys(['street', 'city', 'province', 'postal_code', 'country']);
    });

    it('gets formatted address', function () {
        $dto = new AddressData(
            street: 'Via Roma 1',
            city: 'Milano',
            province: 'MI',
            postalCode: '20100',
            country: 'Italia',
        );

        expect($dto->getFormattedAddress())->toBe('Via Roma 1, 20100 Milano MI, Italia');
    });

    it('gets formatted address without optional fields', function () {
        $dto = new AddressData(
            street: 'Via Roma 1',
            city: 'Milano',
        );

        expect($dto->getFormattedAddress())->toBe('Via Roma 1, Milano, Italia');
    });

    it('gets coordinates as CoordinatesData', function () {
        $dto = new AddressData(
            street: 'Via Roma 1',
            city: 'Milano',
            latitude: 45.4642,
            longitude: 9.1900,
        );

        $coords = $dto->getCoordinates();

        expect($coords)->toBeInstanceOf(CoordinatesData::class);
        expect($coords->latitude)->toBe(45.4642);
    });

    it('returns null coordinates if not set', function () {
        $dto = new AddressData(
            street: 'Via Roma 1',
            city: 'Milano',
        );

        $coords = $dto->getCoordinates();

        expect($coords)->toBeNull();
    });
});
