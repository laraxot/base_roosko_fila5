<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Modules\Tenant\Actions\Config\ResolveTenantConfigValueAction;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(ResolveTenantConfigValueAction::class);
    $this->tenant = Tenant::factory()->create(['name' => 'test-tenant']);
    $configDir = base_path("tenants/{$this->tenant->name}/config");
    File::makeDirectory($configDir, 0755, true);
    File::put(
        $configDir.'/app.php',
        "<?php return ['name' => 'Tenant App', 'debug' => true, 'nested' => ['key' => 'value']];"
    );
});

afterEach(function () {
    $configDir = base_path("tenants/{$this->tenant->name}/config");
    if (File::exists($configDir)) {
        File::deleteDirectory($configDir);
    }
});

describe('ResolveTenantConfigValueAction', function () {
    it('resolves value from tenant config', function () {
        $value = $this->action->execute('app.name', $this->tenant->name);

        expect($value)->toBe('Tenant App');
    });

    it('resolves nested value using dot notation', function () {
        $value = $this->action->execute('app.nested.key', $this->tenant->name);

        expect($value)->toBe('value');
    });

    it('falls back to global config if not in tenant config', function () {
        Config::set('app.global_only', 'Global Value');

        $value = $this->action->execute('app.global_only', $this->tenant->name);

        expect($value)->toBe('Global Value');
    });

    it('tenant config overrides global config', function () {
        Config::set('app.name', 'Global App');

        $value = $this->action->execute('app.name', $this->tenant->name);

        expect($value)->toBe('Tenant App'); // Tenant value takes precedence
    });

    it('returns default if not found anywhere', function () {
        $value = $this->action->execute('app.non_existent', $this->tenant->name, 'default-value');

        expect($value)->toBe('default-value');
    });

    it('returns null if not found and no default', function () {
        $value = $this->action->execute('app.non_existent', $this->tenant->name);

        expect($value)->toBeNull();
    });

    it('handles non-existent tenant', function () {
        $value = $this->action->execute('app.name', 'non-existent', 'default');

        expect($value)->toBe('default');
    });

    it('handles array default', function () {
        $default = ['fallback' => true];
        $value = $this->action->execute('app.missing', $this->tenant->name, $default);

        expect($value)->toBe($default);
    });
});
