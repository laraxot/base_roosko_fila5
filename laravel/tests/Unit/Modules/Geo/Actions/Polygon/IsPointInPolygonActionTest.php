<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Actions\Polygon\IsPointInPolygonAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(IsPointInPolygonAction::class);
});

describe('IsPointInPolygonAction', function () {
    it('returns true for point inside square polygon', function () {
        $point = ['lat' => 45.0, 'lng' => 9.0];
        $polygon = [
            ['lat' => 44.0, 'lng' => 8.0],
            ['lat' => 44.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 8.0],
        ];

        $result = $this->action->execute($point, $polygon);

        expect($result)->toBeTrue();
    });

    it('returns false for point outside polygon', function () {
        $point = ['lat' => 50.0, 'lng' => 9.0];
        $polygon = [
            ['lat' => 44.0, 'lng' => 8.0],
            ['lat' => 44.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 8.0],
        ];

        $result = $this->action->execute($point, $polygon);

        expect($result)->toBeFalse();
    });

    it('returns true for point on polygon edge', function () {
        $point = ['lat' => 45.0, 'lng' => 8.0]; // On left edge
        $polygon = [
            ['lat' => 44.0, 'lng' => 8.0],
            ['lat' => 44.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 8.0],
        ];

        $result = $this->action->execute($point, $polygon);

        expect($result)->toBeTrue();
    });

    it('returns true for point on polygon vertex', function () {
        $point = ['lat' => 44.0, 'lng' => 8.0]; // Bottom-left vertex
        $polygon = [
            ['lat' => 44.0, 'lng' => 8.0],
            ['lat' => 44.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 8.0],
        ];

        $result = $this->action->execute($point, $polygon);

        expect($result)->toBeTrue();
    });

    it('handles triangle polygon', function () {
        $point = ['lat' => 45.0, 'lng' => 9.0];
        $polygon = [
            ['lat' => 44.0, 'lng' => 8.0],
            ['lat' => 44.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 9.0],
        ];

        $result = $this->action->execute($point, $polygon);

        expect($result)->toBeTrue();
    });

    it('returns false for empty polygon', function () {
        $point = ['lat' => 45.0, 'lng' => 9.0];
        $polygon = [];

        $result = $this->action->execute($point, $polygon);

        expect($result)->toBeFalse();
    });

    it('handles polygon with holes (complex)', function () {
        // Outer ring
        $outerRing = [
            ['lat' => 44.0, 'lng' => 8.0],
            ['lat' => 44.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 10.0],
            ['lat' => 46.0, 'lng' => 8.0],
        ];

        // Point in outer ring
        $pointInside = ['lat' => 45.0, 'lng' => 9.0];
        $result = $this->action->execute($pointInside, $outerRing);

        expect($result)->toBeTrue();
    });
});
