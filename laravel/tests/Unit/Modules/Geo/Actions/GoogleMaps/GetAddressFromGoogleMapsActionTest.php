<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Geo\Actions\GoogleMaps\GetAddressFromGoogleMapsAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(GetAddressFromGoogleMapsAction::class);
    Http::fake();
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('GetAddressFromGoogleMapsAction', function () {
    it('returns address for valid coordinates', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/geocode/json*' => Http::response([
                'results' => [
                    [
                        'address_components' => [
                            ['long_name' => 'Piazza del Duomo', 'short_name' => 'Piazza del Duomo', 'types' => ['route']],
                            ['long_name' => 'Milano', 'short_name' => 'Milano', 'types' => ['locality', 'political']],
                            ['long_name' => 'Milano', 'short_name' => 'MI', 'types' => ['administrative_area_level_2', 'political']],
                            ['long_name' => 'Lombardia', 'short_name' => 'Lombardia', 'types' => ['administrative_area_level_1', 'political']],
                            ['long_name' => 'Italia', 'short_name' => 'IT', 'types' => ['country', 'political']],
                            ['long_name' => '20121', 'short_name' => '20121', 'types' => ['postal_code']],
                        ],
                        'formatted_address' => 'Piazza del Duomo, 20121 Milano MI, Italia',
                        'geometry' => [
                            'location' => ['lat' => 45.4642, 'lng' => 9.1900],
                            'location_type' => 'ROOFTOP',
                        ],
                        'place_id' => 'ChIJ...',
                        'types' => ['street_address'],
                    ],
                ],
                'status' => 'OK',
            ], 200),
        ]);

        $result = $this->action->execute(45.4642, 9.1900);

        expect($result)->toBeArray()
            ->toHaveKeys(['formatted_address', 'address_components', 'geometry']);
        expect($result['formatted_address'])->toContain('Milano');
    });

    it('throws exception for ZERO_RESULTS', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/geocode/json*' => Http::response([
                'results' => [],
                'status' => 'ZERO_RESULTS',
            ], 200),
        ]);

        expect(fn () => $this->action->execute(0, 0))
            ->toThrow(Exception::class, 'Nessun risultato trovato');
    });

    it('throws exception for OVER_QUERY_LIMIT', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/geocode/json*' => Http::response([
                'status' => 'OVER_QUERY_LIMIT',
                'error_message' => 'You have exceeded your daily limit.',
            ], 200),
        ]);

        expect(fn () => $this->action->execute(45.4642, 9.1900))
            ->toThrow(Exception::class, 'Limite query superato');
    });

    it('throws exception for REQUEST_DENIED', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/geocode/json*' => Http::response([
                'status' => 'REQUEST_DENIED',
                'error_message' => 'API key not valid.',
            ], 200),
        ]);

        expect(fn () => $this->action->execute(45.4642, 9.1900))
            ->toThrow(Exception::class, 'Richiesta negata');
    });

    it('includes API key in request', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/geocode/json*' => Http::response([
                'results' => [['formatted_address' => 'Test', 'geometry' => ['location' => ['lat' => 45, 'lng' => 9]]]],
                'status' => 'OK',
            ], 200),
        ]);

        $this->action->execute(45.4642, 9.1900);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'key=');
        });
    });
});
