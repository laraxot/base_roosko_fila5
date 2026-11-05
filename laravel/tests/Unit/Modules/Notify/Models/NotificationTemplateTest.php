<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Notify\Models\NotificationTemplate;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('NotificationTemplate Model', function () {
    it('creates template with required fields', function () {
        $template = NotificationTemplate::create([
            'code' => 'welcome-email',
            'name' => 'Welcome Email',
            'subject' => 'Welcome {{name}}!',
            'body_text' => 'Welcome {{name}} to our platform.',
            'body_html' => '<p>Welcome {{name}} to our platform.</p>',
            'channels' => ['mail', 'database'],
            'is_active' => true,
        ]);

        expect($template)->toBeInstanceOf(NotificationTemplate::class);
        expect($template->code)->toBe('welcome-email');
        expect($template->channels)->toBe(['mail', 'database']);
        expect($template->is_active)->toBeTrue();
    });

    it('compiles template with data', function () {
        $template = NotificationTemplate::create([
            'code' => 'test-template',
            'name' => 'Test',
            'subject' => 'Hello {{name}}',
            'body_text' => 'Hi {{name}}, your order {{order_id}} is ready.',
            'body_html' => '<p>Hi {{name}}, your order {{order_id}} is ready.</p>',
            'channels' => ['database'],
            'is_active' => true,
        ]);

        $compiled = $template->compile(['name' => 'John', 'order_id' => 123]);

        expect($compiled['subject'])->toBe('Hello John');
        expect($compiled['body_text'])->toBe('Hi John, your order 123 is ready.');
        expect($compiled['body_html'])->toBe('<p>Hi John, your order 123 is ready.</p>');
    });

    it('handles missing variables gracefully', function () {
        $template = NotificationTemplate::create([
            'code' => 'partial-template',
            'name' => 'Partial',
            'subject' => 'Hello {{name}}',
            'body_text' => 'Hi {{name}}, order {{order_id}}',
            'channels' => ['database'],
            'is_active' => true,
        ]);

        $compiled = $template->compile(['name' => 'John']); // order_id missing

        expect($compiled['subject'])->toBe('Hello John');
        expect($compiled['body_text'])->toContain('{{order_id}}'); // Unreplaced
    });

    it('evaluates shouldSend conditions', function () {
        $template = NotificationTemplate::create([
            'code' => 'conditional',
            'name' => 'Conditional',
            'subject' => 'Test',
            'body_text' => 'Test',
            'channels' => ['database'],
            'is_active' => true,
            'conditions' => ['min_amount' => 100],
        ]);

        expect($template->shouldSend(['amount' => 150]))->toBeTrue();
        expect($template->shouldSend(['amount' => 50]))->toBeFalse();
        expect($template->shouldSend([]))->toBeFalse();
    });

    it('returns true for shouldSend when no conditions', function () {
        $template = NotificationTemplate::create([
            'code' => 'unconditional',
            'name' => 'Unconditional',
            'subject' => 'Test',
            'body_text' => 'Test',
            'channels' => ['database'],
            'is_active' => true,
        ]);

        expect($template->shouldSend([]))->toBeTrue();
        expect($template->shouldSend(['any' => 'data']))->toBeTrue();
    });

    it('has unique code', function () {
        NotificationTemplate::factory()->create(['code' => 'unique-code']);

        expect(fn () => NotificationTemplate::factory()->create(['code' => 'unique-code']))
            ->toThrow(QueryException::class);
    });

    it('scopes active templates', function () {
        NotificationTemplate::factory()->create(['code' => 'active-1', 'is_active' => true]);
        NotificationTemplate::factory()->create(['code' => 'active-2', 'is_active' => true]);
        NotificationTemplate::factory()->create(['code' => 'inactive', 'is_active' => false]);

        $active = NotificationTemplate::active()->get();

        expect($active)->toHaveCount(2);
        expect($active->pluck('code'))->toContain('active-1', 'active-2');
    });

    it('supports Blade directives in templates', function () {
        $template = NotificationTemplate::create([
            'code' => 'blade-template',
            'name' => 'Blade Template',
            'subject' => 'Order @if($urgent) URGENT @endif #{{id}}',
            'body_text' => '@foreach($items as $item) - {{ $item }} @endforeach',
            'channels' => ['database'],
            'is_active' => true,
        ]);

        $compiled = $template->compile([
            'urgent' => true,
            'id' => 456,
            'items' => ['Apple', 'Banana'],
        ]);

        expect($compiled['subject'])->toContain('URGENT');
        expect($compiled['subject'])->toContain('#456');
        expect($compiled['body_text'])->toContain('Apple');
        expect($compiled['body_text'])->toContain('Banana');
    });
});
