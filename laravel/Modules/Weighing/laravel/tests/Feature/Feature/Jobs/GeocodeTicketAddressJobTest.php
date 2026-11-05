<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Modules\Fixcity\Jobs\GeocodeTicketAddressJob;
use Modules\Fixcity\Models\Ticket;
use Modules\User\Models\User;

beforeEach(function (): void {
    Queue::fake();
    Cache::flush();
});

test('geocoding job is dispatched when ticket with coordinates is created', function (): void {
    $user = User::factory()->create();

    Ticket::factory()->create([
        'owner_id' => $user->id,
        'latitude' => 45.4654219,
        'longitude' => 9.1859243,
        'address' => null,
    ]);

    Queue::assertPushed(GeocodeTicketAddressJob::class);
});

test('geocoding job is NOT dispatched when ticket WITHOUT coordinates is created', function (): void {
    $user = User::factory()->create();

    Ticket::factory()->create([
        'owner_id' => $user->id,
        'latitude' => null,
        'longitude' => null,
    ]);

    Queue::assertNotPushed(GeocodeTicketAddressJob::class);
});

test('geocoding job fetches address from Nominatim successfully', function (): void {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'owner_id' => $user->id,
        'latitude' => 45.4654219,
        'longitude' => 9.1859243,
        'address' => null,
    ]);

    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([
            'display_name' => 'Piazza Duomo, Milano, Italia',
            'address' => [
                'road' => 'Piazza Duomo',
                'city' => 'Milano',
                'country' => 'Italia',
            ],
        ], 200),
    ]);

    $job = new GeocodeTicketAddressJob($ticket);
    $job->handle();

    expect($ticket->fresh()->address)->toBe('Piazza Duomo, Milano, Italia');
});

test('geocoding job caches the result for 30 days', function (): void {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'owner_id' => $user->id,
        'latitude' => 45.4654219,
        'longitude' => 9.1859243,
        'address' => null,
    ]);

    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([
            'display_name' => 'Piazza Duomo, Milano, Italia',
        ], 200),
    ]);

    $job = new GeocodeTicketAddressJob($ticket);
    $job->handle();

    $cacheKey = "geocode:{$ticket->latitude},{$ticket->longitude}";
    expect(Cache::has($cacheKey))->toBeTrue();
    expect(Cache::get($cacheKey))->toBe('Piazza Duomo, Milano, Italia');

    Http::assertSentCount(1);

    // Second call should use cache, NO additional HTTP request
    $job2 = new GeocodeTicketAddressJob($ticket);
    $job2->handle();

    Http::assertSentCount(1); // Still 1, not 2!
});

test('geocoding job handles API failure gracefully', function (): void {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'owner_id' => $user->id,
        'latitude' => 45.4654219,
        'longitude' => 9.1859243,
        'address' => null,
    ]);

    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([], 500),
    ]);

    $job = new GeocodeTicketAddressJob($ticket);
    $job->handle();

    expect($ticket->fresh()->address)->toBeNull();
});

test('geocoding job is dispatched when coordinates are updated', function (): void {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'owner_id' => $user->id,
        'latitude' => 45.0,
        'longitude' => 9.0,
    ]);

    Queue::fake();

    $ticket->update([
        'latitude' => 45.4654219,
        'longitude' => 9.1859243,
    ]);

    Queue::assertPushed(GeocodeTicketAddressJob::class);
});
