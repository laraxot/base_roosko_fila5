<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Modules\Tenant\Actions\Translations\TranslateTenantKeyAction;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(TranslateTenantKeyAction::class);
    $this->tenant = Tenant::factory()->create(['name' => 'test-tenant']);
    $langDir = base_path("tenants/{$this->tenant->name}/lang");
    File::makeDirectory($langDir.'/en', 0755, true);
    File::put(
        $langDir.'/en/messages.php',
        "<?php return ['welcome' => 'Welcome to Tenant!', 'custom' => 'Custom: :name'];"
    );
});

afterEach(function () {
    $langDir = base_path("tenants/{$this->tenant->name}/lang");
    if (File::exists($langDir)) {
        File::deleteDirectory($langDir);
    }
});

describe('TranslateTenantKeyAction', function () {
    it('translates key from tenant lang files', function () {
        $translation = $this->action->execute('messages.welcome', $this->tenant->name, 'en');

        expect($translation)->toBe('Welcome to Tenant!');
    });

    it('falls back to global translation if not in tenant', function () {
        Lang::addLines(['global.key' => 'Global Translation'], 'en', 'global');

        $translation = $this->action->execute('global.key', $this->tenant->name, 'en');

        expect($translation)->toBe('Global Translation');
    });

    it('returns key if translation not found anywhere', function () {
        $translation = $this->action->execute('non.existent.key', $this->tenant->name, 'en');

        expect($translation)->toBe('non.existent.key');
    });

    it('supports parameter replacement', function () {
        $translation = $this->action->execute('messages.custom', $this->tenant->name, 'en', ['name' => 'John']);

        expect($translation)->toBe('Custom: John');
    });

    it('handles different locales', function () {
        $langDir = base_path("tenants/{$this->tenant->name}/lang");
        File::makeDirectory($langDir.'/it', 0755, true);
        File::put(
            $langDir.'/it/messages.php',
            "<?php return ['welcome' => 'Benvenuto nel Tenant!'];"
        );

        $translation = $this->action->execute('messages.welcome', $this->tenant->name, 'it');

        expect($translation)->toBe('Benvenuto nel Tenant!');
    });

    it('handles non-existent tenant', function () {
        $translation = $this->action->execute('messages.welcome', 'non-existent', 'en');

        expect($translation)->toBe('messages.welcome'); // Returns key
    });
});
