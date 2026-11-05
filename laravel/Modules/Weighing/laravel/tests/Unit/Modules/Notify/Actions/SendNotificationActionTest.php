<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Modules\Notify\Actions\SendNotificationAction;
use Modules\Notify\Models\NotificationTemplate;
use Modules\Notify\Notifications\GenericNotification;
use Modules\User\Models\User;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(SendNotificationAction::class);
    $this->user = User::factory()->create(['email' => 'test@example.com', 'phone' => '+393001234567']);
    Notification::fake();
});

describe('SendNotificationAction', function () {
    it('sends notification via database channel', function () {
        $template = NotificationTemplate::create([
            'code' => 'test-notification',
            'name' => 'Test Notification',
            'subject' => 'Test Subject',
            'body_text' => 'Test body for {{name}}',
            'body_html' => '<p>Test body for {{name}}</p>',
            'channels' => ['database'],
            'is_active' => true,
        ]);

        $notification = $this->action->handle($this->user, 'test-notification', ['name' => 'John']);

        expect($notification)->not->toBeNull();
        expect($notification->type)->toBe('generic');
        expect($notification->message)->toContain('John');
        Notification::assertSentTo($this->user, GenericNotification::class);
    });

    it('sends notification via mail channel', function () {
        $template = NotificationTemplate::create([
            'code' => 'welcome-email',
            'name' => 'Welcome Email',
            'subject' => 'Welcome {{name}}!',
            'body_text' => 'Welcome {{name}} to our platform',
            'body_html' => '<p>Welcome {{name}} to our platform</p>',
            'channels' => ['mail'],
            'is_active' => true,
        ]);

        $notification = $this->action->handle($this->user, 'welcome-email', ['name' => 'John']);

        expect($notification)->toBeNull(); // Mail channel returns null
        Notification::assertSentTo($this->user, GenericNotification::class);
    });

    it('throws exception for non-existent template', function () {
        expect(fn () => $this->action->handle($this->user, 'non-existent-template', []))
            ->toThrow(Exception::class, 'Template non-existent-template non trovato o non attivo');
    });

    it('throws exception for inactive template', function () {
        NotificationTemplate::create([
            'code' => 'inactive-template',
            'name' => 'Inactive',
            'subject' => 'Test',
            'body_text' => 'Test',
            'channels' => ['database'],
            'is_active' => false,
        ]);

        expect(fn () => $this->action->handle($this->user, 'inactive-template', []))
            ->toThrow(Exception::class, 'Template inactive-template non trovato o non attivo');
    });

    it('respects shouldSend condition', function () {
        $template = NotificationTemplate::create([
            'code' => 'conditional-notification',
            'name' => 'Conditional',
            'subject' => 'Test',
            'body_text' => 'Test',
            'channels' => ['database'],
            'is_active' => true,
            'conditions' => ['min_age' => 18],
        ]);

        // User data doesn't meet condition
        $notification = $this->action->handle($this->user, 'conditional-notification', ['age' => 16]);

        expect($notification)->toBeNull();
    });

    it('allows overriding channels', function () {
        $template = NotificationTemplate::create([
            'code' => 'multi-channel',
            'name' => 'Multi Channel',
            'subject' => 'Test',
            'body_text' => 'Test',
            'channels' => ['database', 'mail'],
            'is_active' => true,
        ]);

        // Override to only use database
        $notification = $this->action->handle($this->user, 'multi-channel', [], ['database']);

        expect($notification)->not->toBeNull();
    });

    it('sends to multiple channels', function () {
        $template = NotificationTemplate::create([
            'code' => 'multi-test',
            'name' => 'Multi Test',
            'subject' => 'Test',
            'body_text' => 'Test',
            'channels' => ['database', 'mail'],
            'is_active' => true,
        ]);

        $notification = $this->action->handle($this->user, 'multi-test', []);

        expect($notification)->not->toBeNull();
        // Database notification should be returned
        expect($notification->channels)->toContain('database');
    });

    it('stores notification data correctly', function () {
        $template = NotificationTemplate::create([
            'code' => 'data-test',
            'name' => 'Data Test',
            'subject' => 'Subject {{var}}',
            'body_text' => 'Body {{var}}',
            'channels' => ['database'],
            'is_active' => true,
        ]);

        $notification = $this->action->handle($this->user, 'data-test', ['var' => 'value'], [], ['custom' => 'option']);

        expect($notification->data['template_code'])->toBe('data-test');
        expect($notification->data['payload']['var'])->toBe('value');
        expect($notification->data['options']['custom'])->toBe('option');
    });
});
