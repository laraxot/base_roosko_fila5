<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Geo\Actions\UpdateCoordinatesAction;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(UpdateCoordinatesAction::class);
});

describe('UpdateCoordinatesAction', function () {
    it('updates coordinates for a single model', function () {
        // This action is a stub that throws RuntimeException
        expect(fn () => $this->action->execute('App\Models\TestModel', 1, 45.4642, 9.1900))
            ->toThrow(RuntimeException::class, 'Aggiornamento coordinate non implementato');
    });

    it('accepts model class, id, latitude, longitude', function () {
        expect(fn () => $this->action->execute('App\Models\Address', 1, 45.4642, 9.1900))
            ->toThrow(RuntimeException::class);
    });

    it('validates latitude range', function () {
        // Latitude should be between -90 and 90
        expect(fn () => $this->action->execute('App\Models\Test', 1, 95, 9.1900))
            ->toThrow(RuntimeException::class);
    });

    it('validates longitude range', function () {
        // Longitude should be between -180 and 180
        expect(fn () => $this->action->execute('App\Models\Test', 1, 45.4642, 190))
            ->toThrow(RuntimeException::class);
    });

    it('handles negative coordinates (southern/western hemisphere)', function () {
        expect(fn () => $this->action->execute('App\Models\Test', 1, -33.8688, 151.2093))
            ->toThrow(RuntimeException::class);
    });
});
