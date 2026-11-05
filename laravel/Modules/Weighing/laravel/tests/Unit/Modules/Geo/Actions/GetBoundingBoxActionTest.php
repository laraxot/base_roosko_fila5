<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Actions\GetBoundingBoxAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(GetBoundingBoxAction::class);
});

describe('GetBoundingBoxAction', function () {
    it('calculates bounding box from coordinates array', function () {
        $coordinates = [
            ['lat' => 45.4642, 'lng' => 9.1900],   // Milano
            ['lat' => 41.9028, 'lng' => 12.4964],  // Roma
            ['lat' => 40.8518, 'lng' => 14.2681],  // Napoli
        ];

        $result = $this->action->execute($coordinates);

        expect($result)->toBeArray()
            ->toHaveKeys(['minLat', 'maxLat', 'minLng', 'maxLng']);

        expect($result['minLat'])->toBe(40.8518);
        expect($result['maxLat'])->toBe(45.4642);
        expect($result['minLng'])->toBe(9.1900);
        expect($result['maxLng'])->toBe(14.2681);
    });

    it('handles single coordinate', function () {
        $coordinates = [
            ['lat' => 45.4642, 'lng' => 9.1900],
        ];

        $result = $this->action->execute($coordinates);

        expect($result['minLat'])->toBe(45.4642);
        expect($result['maxLat'])->toBe(45.4642);
        expect($result['minLng'])->toBe(9.1900);
        expect($result['maxLng'])->toBe(9.1900);
    });

    it('handles empty array', function () {
        $result = $this->action->execute([]);

        expect($result)->toBeNull();
    });

    it('handles negative coordinates', function () {
        $coordinates = [
            ['lat' => -33.8688, 'lng' => 151.2093],  // Sydney
            ['lat' => -37.8136, 'lng' => 144.9631],  // Melbourne
        ];

        $result = $this->action->execute($coordinates);

        expect($result['minLat'])->toBe(-37.8136);
        expect($result['maxLat'])->toBe(-33.8688);
        expect($result['minLng'])->toBe(144.9631);
        expect($result['maxLng'])->toBe(151.2093);
    });

    it('handles coordinates crossing antimeridian', function () {
        $coordinates = [
            ['lat' => 0, 'lng' => 179],
            ['lat' => 0, 'lng' => -179],
        ];

        $result = $this->action->execute($coordinates);

        expect($result)->toHaveKeys(['minLat', 'maxLat', 'minLng', 'maxLng']);
    });
});
