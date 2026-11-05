<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Geo\Actions\GoogleMaps\CalculateTravelTimeAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(CalculateTravelTimeAction::class);
    Http::fake();
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('CalculateTravelTimeAction', function () {
    it('returns travel time between two points', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/distancematrix/json*' => Http::response([
                'destination_addresses' => ['Roma, Italia'],
                'origin_addresses' => ['Milano, Italia'],
                'rows' => [[
                    'elements' => [[
                        'distance' => ['text' => '570 km', 'value' => 570000],
                        'duration' => ['text' => '5 hours 30 mins', 'value' => 19800],
                        'duration_in_traffic' => ['text' => '6 hours 15 mins', 'value' => 22500],
                        'status' => 'OK',
                    ]],
                ]],
                'status' => 'OK',
            ], 200),
        ]);

        $result = $this->action->execute('Milano, Italia', 'Roma, Italia');

        expect($result)->toBeArray()
            ->toHaveKeys(['distance', 'duration', 'duration_in_traffic']);
        expect($result['duration']['value'])->toBe(19800);
        expect($result['duration_in_traffic']['value'])->toBe(22500);
    });

    it('supports different travel modes', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/distancematrix/json*' => Http::response([
                'rows' => [[['elements' => [['duration' => ['value' => 3600], 'status' => 'OK']]]]],
                'status' => 'OK',
            ], 200),
        ]);

        $result = $this->action->execute('A', 'B', 'transit');

        expect($result)->toHaveKey('duration');
    });

    it('supports departure time for traffic', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/distancematrix/json*' => Http::response([
                'rows' => [[['elements' => [['duration_in_traffic' => ['value' => 7200], 'status' => 'OK']]]]],
                'status' => 'OK',
            ], 200),
        ]);

        $departureTime = time() + 3600; // 1 hour from now
        $result = $this->action->execute('A', 'B', 'driving', $departureTime);

        expect($result)->toHaveKey('duration_in_traffic');
    });

    it('handles ZERO_RESULTS', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/distancematrix/json*' => Http::response([
                'status' => 'ZERO_RESULTS',
            ], 200),
        ]);

        expect(fn () => $this->action->execute('Invalid', 'Invalid'))
            ->toThrow(Exception::class);
    });
});
