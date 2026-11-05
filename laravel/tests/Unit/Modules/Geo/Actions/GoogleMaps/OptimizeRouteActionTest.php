<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Geo\Actions\GoogleMaps\OptimizeRouteAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(OptimizeRouteAction::class);
    Http::fake();
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('OptimizeRouteAction', function () {
    it('optimizes route for multiple waypoints', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/directions/json*' => Http::response([
                'routes' => [
                    [
                        'legs' => [
                            ['distance' => ['value' => 10000], 'duration' => ['value' => 1200]],
                            ['distance' => ['value' => 15000], 'duration' => ['value' => 1800]],
                        ],
                        'waypoint_order' => [1, 0],
                        'overview_polyline' => ['points' => 'encoded_polyline'],
                    ],
                ],
                'status' => 'OK',
            ], 200),
        ]);

        $origin = 'Milano, Italia';
        $destination = 'Roma, Italia';
        $waypoints = ['Bologna, Italia', 'Firenze, Italia'];

        $result = $this->action->execute($origin, $destination, $waypoints);

        expect($result)->toBeArray()
            ->toHaveKeys(['routes', 'waypoint_order']);
        expect($result['waypoint_order'])->toBe([1, 0]); // Optimized order
    });

    it('handles single waypoint', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/directions/json*' => Http::response([
                'routes' => [[
                    'legs' => [['distance' => ['value' => 5000], 'duration' => ['value' => 600]]],
                    'waypoint_order' => [0],
                ]],
                'status' => 'OK',
            ], 200),
        ]);

        $result = $this->action->execute('A', 'B', ['C']);

        expect($result['waypoint_order'])->toBe([0]);
    });

    it('throws exception for ZERO_RESULTS', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/directions/json*' => Http::response([
                'status' => 'ZERO_RESULTS',
            ], 200),
        ]);

        expect(fn () => $this->action->execute('Invalid', 'Invalid', []))
            ->toThrow(Exception::class);
    });

    it('supports optimize:true parameter', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/directions/json*' => Http::response([
                'routes' => [[
                    'legs' => [['distance' => ['value' => 10000], 'duration' => ['value' => 1200]]],
                    'waypoint_order' => [0],
                ]],
                'status' => 'OK',
            ], 200),
        ]);

        $result = $this->action->execute('A', 'B', ['C'], true);

        expect($result)->toHaveKey('waypoint_order');
    });
});
