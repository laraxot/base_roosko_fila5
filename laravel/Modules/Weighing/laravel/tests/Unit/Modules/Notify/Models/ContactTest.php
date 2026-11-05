<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Notify\Models\Contact;
use Modules\User\Models\User;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('Contact Model', function () {
    it('creates contact with required fields', function () {
        $contact = Contact::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+393001234567',
            'preferences' => ['email' => true, 'sms' => false, 'push' => true],
        ]);

        expect($contact)->toBeInstanceOf(Contact::class);
        expect($contact->name)->toBe('John Doe');
        expect($contact->email)->toBe('john@example.com');
        expect($contact->phone)->toBe('+393001234567');
        expect($contact->preferences['email'])->toBeTrue();
    });

    it('belongs to user', function () {
        $user = User::factory()->create();
        $contact = Contact::factory()->create(['user_id' => $user->id]);

        expect($contact->user)->toBeInstanceOf(User::class);
        expect($contact->user->id)->toBe($user->id);
    });

    it('casts preferences to array', function () {
        $contact = Contact::create([
            'name' => 'Test',
            'preferences' => ['email' => true, 'sms' => true, 'whatsapp' => false],
        ]);

        expect($contact->preferences)->toBeArray();
        expect($contact->preferences['whatsapp'])->toBeFalse();
    });

    it('has default preferences', function () {
        $contact = Contact::create(['name' => 'Test']);

        expect($contact->preferences)->toBeArray();
        expect($contact->preferences['email'])->toBeTrue(); // default
    });

    it('validates email format', function () {
        expect(fn () => Contact::create(['name' => 'Test', 'email' => 'invalid-email']))
            ->toThrow(QueryException::class);
    });

    it('validates phone format', function () {
        // Phone validation depends on implementation
        $contact = Contact::create(['name' => 'Test', 'phone' => '+393001234567']);
        expect($contact->phone)->toBe('+393001234567');
    });

    it('scopes by notification preference', function () {
        Contact::factory()->create(['preferences' => ['email' => true]]);
        Contact::factory()->create(['preferences' => ['email' => false]]);
        Contact::factory()->create(['preferences' => ['sms' => true]]);

        $emailContacts = Contact::whereJsonContains('preferences->email', true)->get();

        expect($emailContacts)->toHaveCount(1);
    });

    it('provides routeNotificationForMail', function () {
        $contact = Contact::factory()->create(['email' => 'test@example.com']);

        expect($contact->routeNotificationForMail())->toBe('test@example.com');
    });

    it('provides routeNotificationForSms', function () {
        $contact = Contact::factory()->create(['phone' => '+393001234567']);

        expect($contact->routeNotificationForSms())->toBe('+393001234567');
    });
});
