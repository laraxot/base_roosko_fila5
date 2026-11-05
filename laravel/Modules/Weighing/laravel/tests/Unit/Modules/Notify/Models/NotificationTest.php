<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Notify\Models\Notification;
use Modules\User\Models\User;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('Notification Model', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('creates notification with required fields', function () {
        $notification = Notification::create([
            'type' => 'info',
            'message' => 'Test notification',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->getKey(),
            'channels' => ['database'],
            'status' => 'sent',
            'data' => ['key' => 'value'],
        ]);

        expect($notification)->toBeInstanceOf(Notification::class);
        expect($notification->type)->toBe('info');
        expect($notification->message)->toBe('Test notification');
        expect($notification->status)->toBe('sent');
        expect($notification->data['key'])->toBe('value');
    });

    it('belongs to notifiable (polymorphic)', function () {
        $notification = Notification::factory()->create([
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->getKey(),
        ]);

        expect($notification->notifiable)->toBeInstanceOf(User::class);
        expect($notification->notifiable->id)->toBe($this->user->id);
    });

    it('casts data to array', function () {
        $notification = Notification::create([
            'type' => 'info',
            'message' => 'Test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->getKey(),
            'channels' => ['database'],
            'status' => 'sent',
            'data' => ['nested' => ['key' => 'value']],
        ]);

        expect($notification->data)->toBeArray();
        expect($notification->data['nested']['key'])->toBe('value');
    });

    it('casts channels to array', function () {
        $notification = Notification::create([
            'type' => 'info',
            'message' => 'Test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->getKey(),
            'channels' => ['database', 'mail'],
            'status' => 'sent',
        ]);

        expect($notification->channels)->toBeArray();
        expect($notification->channels)->toContain('database');
        expect($notification->channels)->toContain('mail');
    });

    it('sets sent_at on creation when status is sent', function () {
        $notification = Notification::create([
            'type' => 'info',
            'message' => 'Test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->getKey(),
            'channels' => ['database'],
            'status' => 'sent',
        ]);

        expect($notification->sent_at)->not->toBeNull();
    });

    it('does not set sent_at for pending status', function () {
        $notification = Notification::create([
            'type' => 'info',
            'message' => 'Test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->getKey(),
            'channels' => ['database'],
            'status' => 'pending',
        ]);

        expect($notification->sent_at)->toBeNull();
    });

    it('scopes by type', function () {
        Notification::factory()->create(['type' => 'info', 'notifiable_id' => $this->user->id]);
        Notification::factory()->create(['type' => 'warning', 'notifiable_id' => $this->user->id]);
        Notification::factory()->create(['type' => 'error', 'notifiable_id' => $this->user->id]);

        $infoNotifications = Notification::where('type', 'info')->get();

        expect($infoNotifications)->toHaveCount(1);
    });

    it('scopes by status', function () {
        Notification::factory()->create(['status' => 'sent', 'notifiable_id' => $this->user->id]);
        Notification::factory()->create(['status' => 'pending', 'notifiable_id' => $this->user->id]);
        Notification::factory()->create(['status' => 'failed', 'notifiable_id' => $this->user->id]);

        $sent = Notification::where('status', 'sent')->get();

        expect($sent)->toHaveCount(1);
    });

    it('scopes by notifiable', function () {
        $user2 = User::factory()->create();

        Notification::factory()->create(['notifiable_id' => $this->user->id]);
        Notification::factory()->create(['notifiable_id' => $user2->id]);

        $user1Notifications = Notification::forNotifiable($this->user)->get();

        expect($user1Notifications)->toHaveCount(1);
        expect($user1Notifications->first()->notifiable_id)->toBe($this->user->id);
    });
});
