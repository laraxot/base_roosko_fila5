<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Modules\Tenant\Actions\Config\SaveTenantConfigAction;
use Modules\Tenant\Models\Tenant;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(SaveTenantConfigAction::class);
    $this->tenant = Tenant::factory()->create(['name' => 'test-tenant']);
});

afterEach(function () {
    $configDir = base_path("tenants/{$this->tenant->name}/config");
    if (File::exists($configDir)) {
        File::deleteDirectory($configDir);
    }
});

describe('SaveTenantConfigAction', function () {
    it('saves config array to tenant config file', function () {
        $config = [
            'name' => 'Saved App',
            'debug' => false,
            'settings' => ['theme' => 'dark'],
        ];

        $this->action->execute('custom', $config, $this->tenant->name);

        $saved = include base_path("tenants/{$this->tenant->name}/config/custom.php");
        expect($saved)->toBe($config);
    });

    it('creates config directory if not exists', function () {
        $this->action->execute('new-config', ['key' => 'value'], $this->tenant->name);

        expect(File::exists(base_path("tenants/{$this->tenant->name}/config/new-config.php")))->toBeTrue();
    });

    it('overwrites existing config', function () {
        $this->action->execute('overwrite', ['version' => 1], $this->tenant->name);
        $this->action->execute('overwrite', ['version' => 2], $this->tenant->name);

        $saved = include base_path("tenants/{$this->tenant->name}/config/overwrite.php");
        expect($saved['version'])->toBe(2);
    });

    it('handles nested arrays', function () {
        $config = [
            'database' => [
                'connections' => [
                    'tenant' => ['driver' => 'sqlite', 'database' => ':memory:'],
                ],
            ],
        ];

        $this->action->execute('database', $config, $this->tenant->name);

        $saved = include base_path("tenants/{$this->tenant->name}/config/database.php");
        expect($saved['database']['connections']['tenant']['driver'])->toBe('sqlite');
    });

    it('throws exception for non-array data', function () {
        expect(fn () => $this->action->execute('invalid', 'not-an-array', $this->tenant->name))
            ->toThrow(InvalidArgumentException::class, 'Configuration data must be an array');
    });

    it('throws exception for non-existent tenant', function () {
        expect(fn () => $this->action->execute('test', ['key' => 'value'], 'non-existent'))
            ->toThrow(Exception::class);
    });
});
