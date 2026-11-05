<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Geo\Actions\Nominatim\SearchPlacesAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(SearchPlacesAction::class);
    Http::fake();
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('SearchPlacesAction', function () {
    it('returns places for valid query', function () {
        Http::fake([
            'nominatim.openstreetmap.org/search*' => Http::response([
                [
                    'place_id' => 12345,
                    'licence' => 'Data © OpenStreetMap contributors',
                    'osm_type' => 'node',
                    'osm_id' => 123456,
                    'lat' => '45.4642',
                    'lon' => '9.1900',
                    'display_name' => 'Milano, Lombardia, Italia',
                    'class' => 'place',
                    'type' => 'city',
                    'importance' => 0.8,
                ],
                [
                    'place_id' => 12346,
                    'lat' => '41.9028',
                    'lon' => '12.4964',
                    'display_name' => 'Roma, Lazio, Italia',
                    'class' => 'place',
                    'type' => 'city',
                    'importance' => 0.9,
                ],
            ], 200),
        ]);

        $result = $this->action->execute('Milano');

        expect($result)->toBeArray()->not->toBeEmpty();
        expect($result[0])->toHaveKeys(['display_name', 'lat', 'lon', 'type']);
        expect($result[0]['display_name'])->toContain('Milano');
    });

    it('returns empty array for no results', function () {
        Http::fake([
            'nominatim.openstreetmap.org/search*' => Http::response([], 200),
        ]);

        $result = $this->action->execute('NonExistentPlace12345');

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('limits results to specified count', function () {
        Http::fake([
            'nominatim.openstreetmap.org/search*' => Http::response(array_fill(0, 10, [
                'place_id' => 1,
                'lat' => '45.0',
                'lon' => '9.0',
                'display_name' => 'Test',
                'class' => 'place',
                'type' => 'city',
            ]), 200),
        ]);

        $result = $this->action->execute('Test', 3);

        expect(count($result))->toBe(3);
    });

    it('handles special characters in query', function () {
        Http::fake([
            'nominatim.openstreetmap.org/search*' => Http::response([], 200),
        ]);

        $result = $this->action->execute("Sant'Antonio");

        expect($result)->toBeArray();
    });

    it('handles network error', function () {
        Http::fake([
            'nominatim.openstreetmap.org/search*' => Http::response(null, 503),
        ]);

        expect(fn () => $this->action->execute('Test'))
            ->toThrow(Exception::class);
    });
});
