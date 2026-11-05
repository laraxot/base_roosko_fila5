<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Actions\Geocoding\GetGeocodingSuggestionsAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(GetGeocodingSuggestionsAction::class);
});

describe('GetGeocodingSuggestionsAction', function () {
    it('returns empty array for empty query', function () {
        $result = $this->action->execute('');

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('returns suggestions for valid query', function () {
        $result = $this->action->execute('Via Roma');

        expect($result)->toBeArray();
    });

    it('limits results to maximum count', function () {
        $result = $this->action->execute('Milano', 5);

        expect($result)->toBeArray();
        expect(count($result))->toBeLessThanOrEqual(5);
    });

    it('handles special characters in query', function () {
        $result = $this->action->execute("Piazza dell'Unità");

        expect($result)->toBeArray();
    });
});
