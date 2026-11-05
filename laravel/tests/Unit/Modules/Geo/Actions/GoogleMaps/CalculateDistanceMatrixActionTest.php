<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Geo\Actions\GoogleMaps\CalculateDistanceMatrixAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(CalculateDistanceMatrixAction::class);
    Http::fake();
});

afterEach(function () {
    Http::assertNothingSent();
});

describe('CalculateDistanceMatrixAction', function () {
    it('returns distance matrix for multiple origins and destinations', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/distancematrix/json*' => Http::response([
                'destination_addresses' => ['Roma, Italia', 'Napoli, Italia'],
                'origin_addresses' => ['Milano, Italia'],
                'rows' => [
                    [
                        'elements' => [
                            [
                                'distance' => ['text' => '570 km', 'value' => 570000],
                                'duration' => ['text' => '5 hours 30 mins', 'value' => 19800],
                                'status' => 'OK',
                            ],
                            [
                                'distance' => ['text' => '770 km', 'value' => 770000],
                                'duration' => ['text' => '7 hours 15 mins', 'value' => 26100],
                                'status' => 'OK',
                            ],
                        ],
                    ],
                ],
                'status' => 'OK',
            ], 200),
        ]);

        $origins = ['Milano, Italia'];
        $destinations = ['Roma, Italia', 'Napoli, Italia'];

        $result = $this->action->execute($origins, $destinations);

        expect($result)->toBeArray()
            ->toHaveKeys(['rows', 'origin_addresses', 'destination_addresses']);
        expect($result['rows'][0]['elements'][0]['distance']['value'])->toBe(570000);
        expect($result['rows'][0]['elements'][1]['distance']['value'])->toBe(770000);
    });

    it('handles single origin and destination', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/distancematrix/json*' => Http::response([
                'destination_addresses' => ['Roma, Italia'],
                'origin_addresses' => ['Milano, Italia'],
                'rows' => [[
                    'elements' => [[
                        'distance' => ['text' => '570 km', 'value' => 570000],
                        'duration' => ['text' => '5 hours 30 mins', 'value' => 19800],
                        'status' => 'OK',
                    ]],
                ]],
                'status' => 'OK',
            ], 200),
        ]);

        $result = $this->action->execute(['Milano'], ['Roma']);

        expect($result['rows'][0]['elements'][0]['status'])->toBe('OK');
    });

    it('throws exception for invalid request', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/distancematrix/json*' => Http::response([
                'status' => 'INVALID_REQUEST',
                'error_message' => 'Missing required parameter.',
            ], 200),
        ]);

        expect(fn () => $this->action->execute([], []))
            ->toThrow(Exception::class);
    });

    it('handles NOT_FOUND status for elements', function () {
        Http::fake([
            'maps.googleapis.com/maps/api/distancematrix/json*' => Http::response([
                'destination_addresses' => ['Invalid Place'],
                'origin_addresses' => ['Milano, Italia'],
                'rows' => [[
                    'elements' => [[
                        'status' => 'NOT_FOUND',
                    ]],
                ]],
                'status' => 'OK',
            ], 200),
        ]);

        $result = $this->action->execute(['Milano'], ['Invalid Place']);

        expect($result['rows'][0]['elements'][0]['status'])->toBe('NOT_FOUND');
    });
});
