<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Geo\Actions\Nominatim\ReverseGeocodeAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(ReverseGeocodeAction::class);
    Http::fake();
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('ReverseGeocodeAction', function () {
    it('returns address for valid coordinates', function () {
        Http::fake([
            'nominatim.openstreetmap.org/reverse*' => Http::response([
                'place_id' => 12345,
                'licence' => 'Data © OpenStreetMap contributors',
                'osm_type' => 'way',
                'osm_id' => 123456,
                'lat' => '45.4642',
                'lon' => '9.1900',
                'display_name' => 'Piazza del Duomo, 20121 Milano, Italia',
                'address' => [
                    'road' => 'Piazza del Duomo',
                    'city' => 'Milano',
                    'state' => 'Lombardia',
                    'postcode' => '20121',
                    'country' => 'Italia',
                    'country_code' => 'it',
                ],
            ], 200),
        ]);

        $result = $this->action->execute(45.4642, 9.1900);

        expect($result)->toBeArray()
            ->toHaveKeys(['display_name', 'address', 'lat', 'lon']);
        expect($result['display_name'])->toContain('Milano');
        expect($result['address']['city'])->toBe('Milano');
    });

    it('throws exception for invalid coordinates', function () {
        Http::fake([
            'nominatim.openstreetmap.org/reverse*' => Http::response([
                'error' => 'Unable to geocode',
            ], 400),
        ]);

        expect(fn () => $this->action->execute(999, 999))
            ->toThrow(Exception::class);
    });

    it('handles empty response', function () {
        Http::fake([
            'nominatim.openstreetmap.org/reverse*' => Http::response([], 200),
        ]);

        $result = $this->action->execute(45.4642, 9.1900);

        expect($result)->toBeArray();
    });

    it('includes zoom level in request', function () {
        Http::fake([
            'nominatim.openstreetmap.org/reverse*' => Http::response([
                'display_name' => 'Test Address',
                'address' => [],
            ], 200),
        ]);

        $this->action->execute(45.4642, 9.1900, 18);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'zoom=18');
        });
    });

    it('handles network error gracefully', function () {
        Http::fake([
            'nominatim.openstreetmap.org/reverse*' => Http::response(null, 500),
        ]);

        expect(fn () => $this->action->execute(45.4642, 9.1900))
            ->toThrow(Exception::class);
    });
});
